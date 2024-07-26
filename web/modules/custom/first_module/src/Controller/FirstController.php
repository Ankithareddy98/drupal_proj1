<?php

// namespace Drupal\first_module\Controller;

// use Drupal\Core\Controller\ControllerBase;
// use Drupal\node\Entity\Node;

// class FirstController extends ControllerBase {
//   /**
//    * Returns a simple page.
//    *
//    * @return array
//    *   A render array containing the message.
//    */
//   public function content() {
//     $render_array = [
//       'hello_world' => [
//         '#type' => 'markup',
//         '#markup' => t('Hello World'),
//       ],
//     ];

//     //Initialize the query
//     $query = \Drupal::entityQuery('node');

//     //Add conditions to the query
//     $query->condition('type', 'page');
//     $query->condition('status', 1);

//     //Execute the query
//     $nids = $query->execute();

//     //Load the nodes based on ids returned
//     $nodes = Node::loadMultiple($nids);

//     //prepare items for list
//     $items = [];
//     foreach($nodes as $node) {
//       $items[] = [
//          '#markup' => '<h2>' . $node->getTitle() . '</h2><p>' . $node->body->value . '</p>',
//       ];
//     }

//     $render_array['basic_pages'] = [
//         '#theme' => 'item_list',
//         '#items' => $items,
//     ];

//     return $render_array;
//   }
// }


namespace Drupal\first_module\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use \Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\node\Entity\Node;
use Drupal\first_module\Service\CustomDateFormatterService;

class FirstController extends ControllerBase {

    protected $entityQuery;
    protected $dateFormatter;

    public function __construct(EntityTypeManagerInterface $entity_query, CustomDateFormatterService $date_formatter) { //$entity_query is another variable which is type hinted with queryFactroy
        $this->entityQuery = $entity_query;
        $this->dateFormatter = $date_formatter;
    }

    public static function create(ContainerInterface $container) {
        return new static (
          $container->get('entity_type.manager'),
          $container->get('first_module.dateformatter'));
    }
  /**
   * Returns a page with "Hello World" and titles and bodies of all Basic Pages.
   *
   * @return array
   *   A render array containing the message and the list of basic pages.
   */
  public function content() {

    // Part 1. rendered using template
    $hello_world = 'Welcome to Drupal 10 !!!';

    // Part 2: Titles and bodies of all Basic Pages. Static call (::)
    // Initialize the query for 'node' entity type.
    // $query = \Drupal::entityQuery('node');

    // Add conditions to the query.
    // $query->accessCheck(TRUE);
    // $query->condition('type', 'page'); // Content type "Basic Page".
    // $query->condition('status', 1);    // Published nodes.

    // // Execute the query to get node IDs.
    // $nids = $query->execute();

    // Part 2. Dependency Injection using entity_type.manager
    $query = $this->entityQuery->getStorage('node')->getQuery();
      
      dump($this->entityQuery->getStorage('node')->getQuery()->accessCheck(TRUE)->condition('status',1)->condition('type','pag')->execute());

      $nids = $query
        ->accessCheck(TRUE)
        ->condition('type','page')
        ->condition('status',1)
        ->execute();
    // Load the nodes based on the IDs returned.
    $nodes = Node::loadMultiple($nids);
    //dump($nodes);
    

    // dump($timeSTAMP);

    // { Prepare the items for the list.
    // $items = [];
    // foreach ($nodes as $node) {
    //   $items[] = [
    //     '#markup' => '<h2>' . $node->getTitle() . '</h2><p>' . $node->body->value . '</p>',
    //   ];
    // }

    // Add the list of basic pages to the render array. to display using controller
    // $render_array['basic_pages'] = [
    //   '#theme' => 'item_list',
    //   '#items' => $items,
    // ];
    //dump($items);

    // Return the combined render array.
    //return $render_array; }

    //Prepare the items for the list
    // $items1 = [];
    // foreach ($nodes as $node) {
    //   $formatted_date = $this->dateFormatter->dateChange($node->getCreatedTime());
    //   $items1[] = [
    //     '#markup' => '<p>Created on: ' . $formatted_date . '</p>',
    //   ];
    // }
    // dump($items1);

    $items = [];
    foreach ($nodes as $node) {
        $formatted_date = $this->dateFormatter->dateChange($node->getCreatedTime());
        $items[] = [
            'title' => strip_tags($node->getTitle()),
            'body' => strip_tags($node->body->value),
            'date' => $formatted_date,
        ];
    }

    return [
        '#theme' => 'first_module_template',
        '#first_msg' => $hello_world,
        '#items' => $items, 
    ];
  }
}