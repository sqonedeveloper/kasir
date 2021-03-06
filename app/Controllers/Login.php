<?php namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use CodeIgniter\Controller;
use App\Validation\Login as Validate;

class Login extends Controller {

   protected $helpers = [
      'style'
   ];

   protected $db;

   public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
      parent::initController($request, $response, $logger);

      $this->db = \Config\Database::connect();
   }

   public function index() {
      $this->data = [
         'title' => 'Login',
         'internalJs' => script_tag([
            'http://localhost:8080/vendor.js',
            'http://localhost:8080/login.js'
         ])
      ];

      return view('Login', $this->data);
   }

   public function submit() {
      if ($this->request->isAJAX()) {
         $response = ['status' => false, 'errors' => [], 'msg_response' => ''];
         $post = $this->request->getVar();
         $validate = new Validate();
      
         if ($this->validate($validate->generated($post))) {
            $checkUsers = $this->_resolve_user_login($post['username'], $post['password']);

            if ($checkUsers) {
               $session = \Config\Services::session();
               $session->set('isLogin', true);
               $session->set('username', $checkUsers['username']);
               $session->set('telp', $checkUsers['telp']);

               $response['status'] = true;
               $response['msg_response'] = 'Anda berhasil login, halaman segera dialihkan.';
               $response['role'] = $checkUsers['role'];
            } else {
               $response['msg_response'] = 'Username atau password salah!';
            }
         } else {
            $response['msg_response'] = 'Username atau password anda masukkan salah?';
            $response['errors'] = \Config\Services::validation()->getErrors();
         }
         return $this->response->setJSON($response);
      } else {
         $this->notFound();
      }
   }

   private function _resolve_user_login($username, $password) {
      $table = $this->db->table('tb_users');
      $table->select('*');
      $table->where('username', $username);

      $get = $table->get();
      $data = $get->getRowArray();

      if (isset($data)) {
         $verify = $this->_verify_password_hash($password, $data['password']);
         
         if ($verify) {
            return $data;
         } else {
            return false;
         }
      } else {
         return false;
      }
   }

   private function _verify_password_hash($password, $hash) {
      return password_verify($password, $hash);
   }

   public function logout() {
      $session = \Config\Services::session();
      $session->destroy();
      return redirect()->to('/');
   }

}