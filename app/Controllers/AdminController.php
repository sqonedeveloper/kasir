<?php namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use CodeIgniter\Controller;

class AdminController extends Controller {

   protected $helpers = [
      'style',
      'autoload'
   ];

   public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
      parent::initController($request, $response, $logger);
   }

   public function template($content = []) {
      $internalCss = [];
      if (!empty($content['internalCss'])) {
         foreach ($content['internalCss'] as $key) {
            $internalCss[] = $key;
         }
      }

      $internalJs = [
         'http://localhost:8080/vendor.js',
         'http://localhost:8080/adminTopbar.js',
         'http://localhost:8080/adminSidebar.js'
      ];
      
      if (!empty($content['internalJs'])) {
         foreach ($content['internalJs'] as $key) {
            $internalJs[] = $key;
         }
      }

      $footerJs['navigation'] = $this->_generateNavigation();
      if (!empty($content['footerJs'])) {
         foreach ($content['footerJs'] as $key => $val) {
            $footerJs[$key] = $val;
         }
      }

      $data['title'] = $content['title'];
      $data['internalCss'] = css_tag($internalCss);
      $data['internalJs'] = script_tag($internalJs);
      $data['segment'] = $this->_generateSegment();
      $data['pageType'] = @$content['pageType'];
      $data['footerJs'] = json_encode($footerJs);

      echo view('AdminPanel', $data);
   }

   private function _generateNavigation() {
      $config = [];
      return $config;
   }

   private function _generateSegment() {
      $string = uri_string();
      $exp_string = explode('/', $string);

      $set_segment = [];
      for ($i = 0; $i < count($exp_string); $i++) {
         $set_segment[$i + 1] = $exp_string[$i];
      }

      return json_encode($set_segment);
   }

   public function emptyPost($post = []) {
      $response = [];
      foreach ($post as $key => $val) {
         $response[$key] = '';
      }
      return $response;
   }

   public function notFound() {
      throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
   }

}