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

    $data['ingresos'] = $this->getData('ingreso')['data'];
    $data['suma_ingresos'] = $this->getData('ingreso')['suma'];

    $data['salidas'] = $this->getData('salida')['data'];
    $data['suma_salidas'] = $this->getData('salida')['suma'];


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
  public function getData($tipo)
  {
    $query = db_select('node_field_data', 'nfd')->distinct();
             $query->fields('nfd',['nid','title']);
             $query->addField('nfm','field_monto_value');
             $query->addField('ttfd','name');
             $query->join('node__field_monto','nfm','nfm.entity_id = nfd.nid');
             $query->join('node__field_concepto','nfc','nfc.entity_id = nfd.nid');
             $query->join('taxonomy_term_field_data','ttfd','ttfd.tid = nfc.field_concepto_target_id');
             $query->join('node_revision__field_tipo','nft','nft.entity_id = nfd.nid');
             $query->condition('nfd.type','caja');
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
            ]);
       $suma += $item->field_monto_value;
    }
    return[
      'data' => $result,
      'suma' => $suma
    ];
  }




}
