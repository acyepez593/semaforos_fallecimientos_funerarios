<?php
    
declare(strict_types=1);

namespace App\Http\Controllers\Backend;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Estado;
use App\Models\Expediente;
use App\Models\Proteccion;
use App\Models\Semaforo;
use App\Models\TipoIngreso;
use App\Models\TipoRespuesta;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportesController extends Controller
{
    public function index(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['reporte.view']);

        $tiposReporte=array();

        return view('backend.pages.reportes.index', [
            'tiposReporte' => $tiposReporte,
            'reportes' => [],
            'roles' => Role::all(),
        ]);
    }

    public function create(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['reporte.view']);

        $protecciones = Proteccion::get(["nombre", "id"]);
        $estados = Estado::get(["nombre", "id"]);
        $tiposRespuesta = TipoRespuesta::get(["nombre", "id"]);
        $tiposIngreso = TipoIngreso::get(["nombre", "id"]);
        $semaforos = Semaforo::get(["estado", "id"]);
        $responsables = Admin::get(["name", "id"]);

        return view('backend.pages.reportes.create', [
            'protecciones' => $protecciones,
            'estados' => $estados,
            'tiposRespuesta' => $tiposRespuesta,
            'tiposIngreso' => $tiposIngreso,
            'semaforos' => $semaforos,
            'responsables' => $responsables
        ]);
    }

    public function getReporteByFilters(Request $request): JsonResponse
    {
        
        $data['roles'] = Role::all();
  
        return response()->json($data);
    }

    public function generarReporteExpedientesByFilters(Request $request)
    {
        $this->checkAuthorization(auth()->user(), ['reporte.download']);

        ini_set('memory_limit', '-1'); // anula el limite 

        $expedientes = Expediente::all();
        $semaforos = Semaforo::where('id',">",1)->get();
        $fecha_acutual = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now()->format('Y-m-d'). ' 00:00:00');

        foreach($expedientes as $expediente){
            //determinar a que semaforización pertenece
            $fecha_maxima_respuesta = Carbon::createFromFormat('Y-m-d H:i:s', $expediente->fecha_maxima_respuesta. ' 00:00:00');
            $diferencia_dias = $fecha_acutual->diffInDays($fecha_maxima_respuesta);
            $expediente->semaforo_id = 1;

            if($expediente->observaciones == null && $expediente->observaciones == ""){
                foreach($semaforos as $semaforo){
                    if($semaforo->rango_inicial <= $diferencia_dias && $diferencia_dias <= $semaforo->rango_final){
                        $expediente->semaforo_id = $semaforo->id;
                        $expediente->save();
                    }
                }
            }
        }

        $expedientes = Expediente::where('id',">",0);

        $filtroVictimaSearch = $request->victima_search;
        $filtroIdDeProteccionSearch = $request->id_de_proteccion_search;
        $filtroProteccionIdSearch = json_decode($request->proteccion_id_search, true);
        $filtroPeticionarioNotificadoSearch = $request->peticionario_notificado_search;
        $filtroNroOficioNotificacionSearch = $request->nro_oficio_notificacion_search;
        $filtroFechaNotificacionDesdeSearch = $request->fecha_notificacion_desde_search;
        $filtroFechaNotificacionHastaSearch = $request->fecha_notificacion_hasta_search;
        $filtroResponsablesIdsSearch = json_decode($request->responsables_ids_search, true);
        $filtroFechaMaximaRespuestaDesdeSearch = $request->fecha_maxima_respuesta_desde_search;
        $filtroFechaMaximaRespuestaHastaSearch = $request->fecha_maxima_respuesta_hasta_search;
        $filtroDocumentacionSolicitadaSearch = $request->documentacion_solicitada_search;
        $filtroObservacionesSearch = $request->observaciones_search;
        $filtroTipoRespuestaIdSearch = json_decode($request->tipo_respuesta_id_search, true);
        $filtroTipoIngresoIdSearch = json_decode($request->tipo_ingreso_id_search, true);
        $filtroFechaIngresoExpedienteDesdeSearch = $request->fecha_ingreso_expediente_desde_search;
        $filtroFechaIngresoExpedienteHastaSearch = $request->fecha_ingreso_expediente_hasta_search;
        $filtroEstadoIdSearch = json_decode($request->estado_id_search, true);
        $filtroCreadoPorIdSearch = json_decode($request->creado_por_id_search, true);
        
        if(isset($filtroVictimaSearch) && !empty($filtroVictimaSearch)){
            $expedientes = $expedientes->where('victima', 'like', '%'.$filtroVictimaSearch.'%');
        }
        if(isset($filtroIdDeProteccionSearch) && !empty($filtroIdDeProteccionSearch)){
            $expedientes = $expedientes->where('id_de_proteccion', 'like', '%'.$filtroIdDeProteccionSearch.'%');
        }
        if(isset($filtroProteccionIdSearch) && !empty($filtroProteccionIdSearch)){
            $expedientes = $expedientes->whereIn('proteccion_id', $filtroProteccionIdSearch);
        }
        if(isset($filtroPeticionarioNotificadoSearch) && !empty($filtroPeticionarioNotificadoSearch)){
            $expedientes = $expedientes->where('peticionario_notificado', 'like', '%'.$filtroPeticionarioNotificadoSearch.'%');
        }
        if(isset($filtroNroOficioNotificacionSearch) && !empty($filtroNroOficioNotificacionSearch)){
            $expedientes = $expedientes->where('nro_oficio_notificacion', 'like', '%'.$filtroNroOficioNotificacionSearch.'%');
        }
        if(isset($filtroFechaNotificacionDesdeSearch) && !empty($filtroFechaNotificacionDesdeSearch)){
            $expedientes = $expedientes->where('fecha_notificacion', '>=' , $filtroFechaNotificacionDesdeSearch);
        }
        if(isset($filtroFechaNotificacionHastaSearch) && !empty($filtroFechaNotificacionHastaSearch)){
            $expedientes = $expedientes->where('fecha_notificacion', '<=' , $filtroFechaNotificacionHastaSearch);
        }
        if(isset($filtroResponsablesIdsSearch) && !empty($filtroResponsablesIdsSearch)){
            $expedientes = $expedientes->whereIn('responsables_ids', $filtroResponsablesIdsSearch);
        }
        if(isset($filtroFechaMaximaRespuestaDesdeSearch) && !empty($filtroFechaMaximaRespuestaDesdeSearch)){
            $expedientes = $expedientes->where('fecha_maxima_respuesta', '>=', $filtroFechaMaximaRespuestaDesdeSearch);
        }
        if(isset($filtroFechaMaximaRespuestaHastaSearch) && !empty($filtroFechaMaximaRespuestaHastaSearch)){
            $expedientes = $expedientes->where('fecha_maxima_respuesta', '<=', $filtroFechaMaximaRespuestaHastaSearch);
        }
        if(isset($filtroDocumentacionSolicitadaSearch) && !empty($filtroDocumentacionSolicitadaSearch)){
            $expedientes = $expedientes->where('documentacion_solicitada', 'like', '%'.$filtroDocumentacionSolicitadaSearch.'%');
        }
        if(isset($filtroObservacionesSearch) && !empty($filtroObservacionesSearch)){
            $expedientes = $expedientes->where('observaciones', 'like', '%'.$filtroObservacionesSearch.'%');
        }
        if(isset($filtroTipoRespuestaIdSearch) && !empty($filtroTipoRespuestaIdSearch)){
            $expedientes = $expedientes->whereIn('proteccion_id', $filtroTipoRespuestaIdSearch);
        }
        if(isset($filtroEstadoIdSearch) && !empty($filtroEstadoIdSearch)){
            $expedientes = $expedientes->whereIn('estado_id', $filtroEstadoIdSearch);
        }
        if(isset($filtroCreadoPorIdSearch) && !empty($filtroCreadoPorIdSearch)){
            $expedientes = $expedientes->whereIn('creado_por_id', $filtroCreadoPorIdSearch);
        }
        if(isset($filtroTipoIngresoIdSearch) && !empty($filtroTipoIngresoIdSearch)){
            $expedientes = $expedientes->whereIn('tipo_ingreso_id', $filtroTipoIngresoIdSearch);
        }
        if(isset($filtroFechaIngresoExpedienteDesdeSearch) && !empty($filtroFechaIngresoExpedienteDesdeSearch)){
            $expedientes = $expedientes->where('fecha_ingreso_expediente', '>=', $filtroFechaIngresoExpedienteDesdeSearch);
        }
        if(isset($filtroFechaIngresoExpedienteHastaSearch) && !empty($filtroFechaIngresoExpedienteHastaSearch)){
            $expedientes = $expedientes->where('fecha_ingreso_expediente', '<=', $filtroFechaIngresoExpedienteHastaSearch);
        }
        
        $expedientes = $expedientes->orderBy('id', 'desc')->get();

        $protecciones = Proteccion::get(["nombre", "id"])->pluck('nombre','id');
        $estados = Estado::get(["nombre", "id"])->pluck('nombre','id');
        $tiposRespuesta = TipoRespuesta::get(["nombre", "id"])->pluck('nombre','id');
        $tiposIngreso = TipoIngreso::get(["nombre", "id"])->pluck('nombre','id');
        $semaforos = Semaforo::get(["estado", "id"])->pluck('estado','id');
        $responsables = Admin::get(["name", "id"])->pluck('name','id');

        $fileName = 'FormatoReporteExpedientes.xlsx';

        if(public_path('uploads/'.$fileName)){
            $inputFileName = public_path('reporte/'.$fileName);
            $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);
            $reader = IOFactory::createReader($inputFileType);
            $spreadsheet = $reader->load($inputFileName);

            $active_sheet = $spreadsheet->getActiveSheet();

            $columna = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R'];
            $filaInicial = 4;
            $fila = $filaInicial;

            foreach ($expedientes as $index=>$registro) {
                
                $active_sheet->setCellValue($columna[0].$fila, $index+1);
                $active_sheet->setCellValue($columna[1].$fila, !isset($registro->victima) || empty($registro->victima) ? "" : $registro->victima);
                $active_sheet->setCellValue($columna[2].$fila, !isset($registro->id_de_proteccion) || empty($registro->id_de_proteccion) ? "" : $registro->id_de_proteccion);
                $active_sheet->setCellValue($columna[3].$fila, !isset($registro->proteccion_id) || empty($registro->proteccion_id) || $registro->proteccion_id == 0 ? "" : $protecciones[$registro->proteccion_id]);
                $active_sheet->setCellValue($columna[4].$fila, !isset($registro->peticionario_notificado) || empty($registro->peticionario_notificado) ? "" : $registro->peticionario_notificado);
                $active_sheet->setCellValue($columna[5].$fila, !isset($registro->nro_oficio_notificacion) || empty($registro->nro_oficio_notificacion) ? "" : $registro->nro_oficio_notificacion);
                $active_sheet->setCellValue($columna[6].$fila, !isset($registro->fecha_notificacion) || empty($registro->fecha_notificacion)|| $registro->fecha_notificacion == '0000-00-00' ? "" : $registro->fecha_notificacion);

                $lista_responsables = "";
                
                if(!isset($registro->responsables_ids) || empty($registro->responsables_ids)){
                    $lista_responsables = "";
                }else{
                    $responsables_ids = explode(",", $registro->responsables_ids);
                    $tam_resp_ids = count($responsables_ids);
                    $contador = 1;
                    foreach($responsables_ids as $resp){
                        if($tam_resp_ids == $contador){
                            $lista_responsables .= $responsables[$resp];
                        }else{
                            $lista_responsables .= $responsables[$resp].", ";
                        }
                        $contador = $contador + 1;
                    }
                }
                
                $active_sheet->setCellValue($columna[7].$fila,  $lista_responsables);

                $active_sheet->setCellValue($columna[8].$fila, !isset($registro->fecha_maxima_respuesta) || empty($registro->fecha_maxima_respuesta)|| $registro->fecha_maxima_respuesta == '0000-00-00' ? "" : $registro->fecha_maxima_respuesta);
                $active_sheet->setCellValue($columna[9].$fila, !isset($registro->documentacion_solicitada) || empty($registro->documentacion_solicitada) ? "" : $registro->documentacion_solicitada);
                $active_sheet->setCellValue($columna[10].$fila, !isset($registro->observaciones) || empty($registro->observaciones) ? "" : $registro->observaciones);
                $active_sheet->setCellValue($columna[11].$fila, !isset($registro->tipo_respuesta_id) || empty($registro->tipo_respuesta_id) || $registro->tipo_respuesta_id == 0 ? "" : $tiposRespuesta[$registro->tipo_respuesta_id]);
                $active_sheet->setCellValue($columna[12].$fila, !isset($registro->estado_id) || empty($registro->estado_id) || $registro->estado_id == 0 ? "" : $estados[$registro->estado_id]);
                $active_sheet->setCellValue($columna[13].$fila, !isset($registro->tipo_ingreso_id) || empty($registro->tipo_ingreso_id) || $registro->tipo_ingreso_id == 0 ? "" : $tiposIngreso[$registro->tipo_ingreso_id]);
                $active_sheet->setCellValue($columna[14].$fila, !isset($registro->fecha_ingreso_expediente) || empty($registro->fecha_ingreso_expediente)|| $registro->fecha_ingreso_expediente == '0000-00-00' ? "" : $registro->fecha_ingreso_expediente);
                $active_sheet->setCellValue($columna[15].$fila, !isset($registro->semaforo_id) || empty($registro->semaforo_id) || $registro->semaforo_id == 0 ? "" : $semaforos[$registro->semaforo_id]);
                $active_sheet->setCellValue($columna[16].$fila, !isset($registro->creado_por_id) || empty($registro->creado_por_id) || $registro->creado_por_id == 0 ? "" : $responsables[$registro->creado_por_id]);
                $active_sheet->setCellValue($columna[17].$fila, $registro->es_historico == 1 ? "SI" : "NO");

                $fila += 1;
            }
            $active_sheet->getStyle($columna[0].$filaInicial.':'.$columna[17].$fila-1)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);

            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $filename = "reporte.xlsx";
            $writer->save(storage_path('app/'. $filename));
            $data['status'] = 200;
            $data['message'] = "OK";
            
            return response()->download(storage_path('app/'.$filename));
            
        }else{
            return false;
        }
    }

}