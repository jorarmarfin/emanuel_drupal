<?php

namespace Drupal\flujo_caja\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class DefaultController.
 */
class DefaultController extends ControllerBase {

  /**
   * Mostrar.
   *
   * @return string
   *   Return Hello string.
   */
  public function mostrar() {
    $data = [];
    $data['years'] = $this->getYears();
    $data['months'] = $this->getMonths();
    #Recibo data
    $year = \Drupal::request()->get('anio');
    $mes = \Drupal::request()->get('mes');
    $data['anio'] = $year;
    $data['mes'] = $mes;

    $ingresos = $this->getData('ingreso',$mes,$year);
    $data['ingresos'] = $ingresos['data'];
    $data['suma_ingresos'] = $ingresos['suma'];

    $salidas = $this->getData('salida',$mes,$year);
    $porcentajes = $this->getPorcentajes('ingreso',$mes,$year,$ingresos['suma']);

    $data['porcentajes'] = $porcentajes;

    return [
      '#theme' => 'flujo_caja',
      '#data' => $data,
      '#attached'=>[
          'library'=>[
            'flujo_caja/flujo_caja.librerias'
          ]
      ]
    ];
  }
  private function getYears() {
    return range(date('Y'),2017, -1);
  }
  private function getMonths()
  {
    return [
        ['value'=>1,'title'=>'Enero'],
        ['value'=>2,'title'=>'Febrero'],
        ['value'=>3,'title'=>'Marzo'],
        ['value'=>4,'title'=>'Abril'],
        ['value'=>5,'title'=>'Mayo'],
        ['value'=>6,'title'=>'Junio'],
        ['value'=>7,'title'=>'Julio'],
        ['value'=>8,'title'=>'Agosto'],
        ['value'=>9,'title'=>'Setiembre'],
        ['value'=>10,'title'=>'Octubre'],
        ['value'=>11,'title'=>'Noviembre'],
        ['value'=>12,'title'=>'Diciembre'],
    ];
  }
  public function getData($tipo,$mes,$year)
  {

    $query = db_select('node_field_data', 'nfd')->distinct();
             $query->fields('nfd',['nid','title']);
             $query->addField('nfm','field_monto_value');
             $query->addField('ttfd','name');
             $query->addField('nff','field_fecha_value');
             $query->join('node__field_monto','nfm','nfm.entity_id = nfd.nid');
             $query->join('node__field_concepto','nfc','nfc.entity_id = nfd.nid');
             $query->join('taxonomy_term_field_data','ttfd','ttfd.tid = nfc.field_concepto_target_id');
             $query->join('node__field_tipo','nft','nft.entity_id = nfd.nid');
             $query->join('node__field_fecha','nff','nff.entity_id = nfd.nid');
             $query->join('node__field_tercera_semana','nfts','nfts.entity_id = nfd.nid');
             $query->condition('nfd.type','caja');
             $query->condition('nft.field_tipo_value',$tipo);
             $query->condition('nfts.field_tercera_semana_value',0);
             $query->orderby('nff.field_fecha_value');

    $fecha_inicio = $this->FirstLastDay($mes, $year, 'first');
    $fecha_fin = $this->FirstLastDay($mes, $year, 'last');

    if (strlen($fecha_inicio)>0)
    $query->condition('nff.field_fecha_value',[$fecha_inicio,$fecha_fin],'BETWEEN');



    $query = $query->execute()->fetchAll();
    $result = [];
    $suma = 0;

    foreach ($query as $key => $item) {
       array_push($result,[
                'nid' => $item->nid,
                'title' => $item->title,
                'concepto' => $item->name,
                'cantidad' => $item->field_monto_value,
                'fecha' => $item->field_fecha_value,
            ]);
       $suma += $item->field_monto_value;
    }
    return[
      'data' => $result,
      'suma' => $suma
    ];
  }
  public function getPorcentajes($tipo,$mes,$year,$suma)
  {

    $query = db_select('node_field_data', 'nfd')->distinct();
             $query->fields('nfd',['nid','title']);
             $query->addField('nfm','field_monto_value');
             $query->addField('ttfd','name');
             $query->addField('nff','field_fecha_value');
             $query->join('node__field_monto','nfm','nfm.entity_id = nfd.nid');
             $query->join('node__field_concepto','nfc','nfc.entity_id = nfd.nid');
             $query->join('taxonomy_term_field_data','ttfd','ttfd.tid = nfc.field_concepto_target_id');
             $query->join('node__field_tipo','nft','nft.entity_id = nfd.nid');
             $query->join('node__field_fecha','nff','nff.entity_id = nfd.nid');
             $query->join('node__field_tercera_semana','nfts','nfts.entity_id = nfd.nid');
             $query->condition('nfd.type','caja');
             $query->condition('nft.field_tipo_value',$tipo);
             $query->condition('nfts.field_tercera_semana_value',1);
             $query->orderby('nff.field_fecha_value');

    $fecha_inicio = $this->FirstLastDay($mes, $year, 'first');
    $fecha_fin = $this->FirstLastDay($mes, $year, 'last');

    if (strlen($fecha_inicio)>0)
    $query->condition('nff.field_fecha_value',[$fecha_inicio,$fecha_fin],'BETWEEN');

    $query = $query->execute()->fetchAll();
    $result = [];

    foreach ($query as $key => $item) {
       array_push($result,[
                'nid' => $item->nid,
                'title' => $item->title,
                'concepto' => $item->name,
                'cantidad' => $item->field_monto_value,
                'fecha' => $item->field_fecha_value,
            ]);
    }
    print_r($result);
    return[
      'zona' => $suma*(10/100),
      'diocesis' => $suma*(20/100),
      'sacerdotes' => $suma*(10/100),
      'nacional' => $result[0]['cantidad'],
    ];
  }
  public function FirstLastDay($month,$year,$sw)
  {

    $fecha = '';
    switch ($sw) {
      case 'first':
        $fecha = date('Y-m-d',mktime(0, 0, 0, $month, $day = 1, $year));
        break;
      case 'last':
        $fecha = date('Y-m-d',mktime(0, 0, 0, $month+1, $day = 1, $year)-1);
        break;
    }

    return $fecha;
  }




}
