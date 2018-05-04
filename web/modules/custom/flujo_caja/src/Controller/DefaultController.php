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

    $data['ingresos'] = $this->getData('ingreso',$mes,$year)['data'];
    $data['suma_ingresos'] = $this->getData('ingreso',$mes,$year)['suma'];

    $data['salidas'] = $this->getData('salida',$mes,$year)['data'];
    $data['suma_salidas'] = $this->getData('salida',$mes,$year)['suma'];


    return [
      '#theme' => 'flujo_caja',
      '#data' => $data
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
    $fecha_inicio = $this->FirstLastDay($mes, $year, 'first');
    $fecha_fin = $this->FirstLastDay($mes, $year, 'last');

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
             $query->condition('nfd.type','caja');
             //$query->condition('nff.field_fecha_value',[$fecha_inicio,$fecha_fin],'BETWEEN');
             $query->orderby('nff.field_fecha_value');
             $query->condition('nft.field_tipo_value',$tipo);

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
