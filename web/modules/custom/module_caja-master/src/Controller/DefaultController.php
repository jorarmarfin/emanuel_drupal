<?php

namespace Drupal\module_caja\Controller;

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
    $data['months'] = $this->getMonths();
    $data['years'] = $this->getYears();

    $year = \Drupal::request()->get('anio');
    $mes = \Drupal::request()->get('mes');
    $data['anio'] = $year;
    $data['mes'] = $mes;

    $result = $this->getData('tareas');
    $data['ingresos'] = $result;
    $data['suma'] = $suma;
    return [
      '#theme' => 'module_caja',
      '#data' => $data,
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
             $query->addField('nfc','field_cantidad_value');
             $query->join('node__field_cantidad','nfc','nfc.entity_id = nfd.nid');
             $query->condition('nfd.type',$tipo);

    $query = $query->execute()->fetchAll();
    $result = [];
    $suma = 0;

    foreach ($query as $key => $item) {
       array_push($result,[
                'nid' => $item->nid,
                'title' => $item->title,
                'cantidad' => $item->field_cantidad_value,
            ]);
       $suma += $item->field_cantidad_value;
    }
    return[
      'data' => $result,
    ]
  }




}
