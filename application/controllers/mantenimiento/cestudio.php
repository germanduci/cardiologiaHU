<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Cestudio extends CI_Controller{
    private $permisos;
    function __construct(){
        parent:: __construct();
        if(!$this->session->userdata('login')){
            redirect(base_url()); 
        }
        $this->load->model('mestudio');
        $this->load->model('mreporte');
    }

    public function index(){
        $data = array(
            'estudioindex' => $this->mestudio->mselectestudio(),
        );
        
        if($this->session->userdata('id_rol')==8){
            $this->load->view('layouts/header');
            $this->load->view('layouts/asideMedico');
            $this->load->view('admin/estudio/venviarMedico',$data);
            $this->load->view('layouts/footer');
            }
        elseif($this->session->userdata('id_rol')==1){
            $this->load->view('layouts/header');
            $this->load->view('layouts/asideMedico');
            $this->load->view('admin/estudio/venviar',$data);
            $this->load->view('layouts/footer');
            }
        else{
            $this->load->view('layouts/header');
            $this->load->view('layouts/asideAdministrativo');
            $this->load->view('admin/estudio/venviarAdministrativo',$data);
            $this->load->view('layouts/footer');
            }
    }

    public function cadd(){

    if($this->session->userdata('id_rol')==8){
        $this->load->view('layouts/header');
        $this->load->view('layouts/asideMedico');
        $this->load->view('admin/estudio/vadd');
        $this->load->view('layouts/footer');
        }
    elseif($this->session->userdata('id_rol')==1){
        $this->load->view('layouts/header');
        $this->load->view('layouts/aside');
        $this->load->view('admin/estudio/vadd');
        $this->load->view('layouts/footer');
        }
    else{
        $this->load->view('layouts/header');
        $this->load->view('layouts/asideAdministrativo');
        $this->load->view('admin/estudio/vadd');
        $this->load->view('layouts/footer');
        }
    }

    public function creporte(){         
        
        if($this->session->userdata('id_rol')==8){
            $this->load->view('layouts/header');
            $this->load->view('layouts/asideMedico');
            $this->load->view('admin/estudio/vreporte');
            $this->load->view('layouts/footer');
            }
        elseif($this->session->userdata('id_rol')==1){
            $this->load->view('layouts/header');
            $this->load->view('layouts/aside');
            $this->load->view('admin/estudio/vreporte');
            $this->load->view('layouts/footer');
            }
        else{
            $this->load->view('layouts/header');
            $this->load->view('layouts/asideAdministrativo');
            $this->load->view('admin/estudio/vreporte');
            $this->load->view('layouts/footer');
            }
    }    
    
    public function cenviar(){
        $data = array(
            'estudioindex'=> $this->mestudio->mselectestudio(),
        ); 

        if($this->session->userdata('id_rol')==8){
            $this->load->view('layouts/header');
            $this->load->view('layouts/asideMedico');
            $this->load->view('admin/estudio/venviarMedico',$data);
            $this->load->view('layouts/footer');
            }
        elseif($this->session->userdata('id_rol')==1){
            $this->load->view('layouts/header');
            $this->load->view('layouts/aside');
            $this->load->view('admin/estudio/venviar',$data);
            $this->load->view('layouts/footer');
            }
        else{
            $this->load->view('layouts/header');
            $this->load->view('layouts/asideAdministrativo');
            $this->load->view('admin/estudio/venviarAdministrativo',$data);
            $this->load->view('layouts/footer');
            }
    }

    public function ctodos(){
        $data = array(
            'estudioindex'=> $this->mestudio->mselectestudiotodos(),
        );                
        if($this->session->userdata('id_rol')==8){
            $this->load->view('layouts/header');
            $this->load->view('layouts/asideMedico');
            $this->load->view('admin/estudio/venviarMedico',$data);
            $this->load->view('layouts/footer');
            }
        elseif($this->session->userdata('id_rol')==1){
            $this->load->view('layouts/header');
            $this->load->view('layouts/aside');
            $this->load->view('admin/estudio/venviar',$data);
            $this->load->view('layouts/footer');
            }
        else{
            $this->load->view('layouts/header');
            $this->load->view('layouts/asideAdministrativo');
            $this->load->view('admin/estudio/venviarAdministrativo',$data);
            $this->load->view('layouts/footer');
            }
    } 

    public function cinsert(){
        
        $dnipaciente        = $this->input->post('txtdni_paciente');
        $npaciente    = $this->mestudio->buscarNombre($this->input->post('txtdni_paciente'));
        $apaciente  = $this->mestudio->buscarApellido($this->input->post('txtdni_paciente'));
        $nombre_paciente = $npaciente[0]->nombres;
        $apellido_paciente = $apaciente[0]->apellidos;
        $fecha_estudio      = $this->input->post('txtfecha_estudio');
        $tipo_estudio       = $this->input->post('txttipo_estudio');
        date_default_timezone_set('America/Argentina/Mendoza');                
        $date               = date('Y-m-d');
        $idusuario_subido   = $this->session->userdata('user_name');
        $correo             = $this->mestudio->buscarEmail($this->input->post('txtdni_paciente'));              
        if(!empty($correo[0]->email)){
        $email              = $correo[0]->email;
        }else{
        $email              ="sin@correo";
        }   
        $this->form_validation->set_rules('txtdni_paciente','el dni','required');

        $config['upload_path']='./uploads/files/';
        $config['allowed_types']='pdf';
        $this->load->library('upload', $config);
        if($this->upload->do_upload('txtfile')){
            $archivo = $this->upload->data('file_name');    
        }else{
            echo $this->upload->display_errors();
        }

        if($this->form_validation->run()){
            $data=array(
                'dni_paciente'      =>      $dnipaciente,
                'nombre_paciente'   =>      $nombre_paciente,
                'apellido_paciente' =>      $apellido_paciente,
                'email'             =>      $email,
                'fecha_estudio'     =>      $fecha_estudio,
                'tipo_estudio'      =>      $tipo_estudio,
                'estado_envio'      =>      '0',
                'fecha_subida'      =>      $date,
                'fecha_envio'       =>      '00-00-0000',
                'idusuario_subido'  =>      $idusuario_subido,
                'archivo'           =>      $archivo                
            );
            
            $res=$this->mestudio->minsertestudio($data);
                if($res){
                    $this->session->set_flashdata('correcto','Se guardo correctamente');
                    redirect(base_url().'mantenimiento/cestudio');
                }else{
                    $this->session->set_flashdata('error','No se guardo registro');
                    redirect(base_url().'mantenimiento/cestudio/cadd');
                } 
            }else{
                $this->session->set_flashdata('error','No se pudo cargar estudio');
                $this->cadd();
            }
    }

    public function cedit($id_estudio){
        $data = array(
            'estudioedit'=>$this->mestudio->midupdateestudio($id_estudio)
        );        

        if($this->session->userdata('id_rol')==8){
            $this->load->view('layouts/header');
            $this->load->view('layouts/asideMedico');
            $this->load->view('admin/estudio/vedit',$data);
            $this->load->view('layouts/footer');
            }
        elseif($this->session->userdata('id_rol')==1){
            $this->load->view('layouts/header');
            $this->load->view('layouts/aside');
            $this->load->view('admin/estudio/vedit',$data);
            $this->load->view('layouts/footer');
            }
        else{
            $this->load->view('layouts/header');
            $this->load->view('layouts/asideAdministrativo');
            $this->load->view('admin/estudio/vedit',$data);
            $this->load->view('layouts/footer');
            }
    }

    public function cupdate(){

        $id_estudio         = $this->input->post('txtid_estudio');
        $dni_paciente       = $this->input->post('txtdni_paciente');
        $tipo_estudio       = $this->input->post('txttipo_estudio');
        $fecha_estudio      = $this->input->post('txtfecha_estudio');
        $email              = $this->input->post('txtmail');        

        $estudioActual      = $this->mestudio->midupdateestudio($id_estudio);
        
        if ($id_estudio == $estudioActual -> id_estudio){
            $unique ='';
        }else{
            $unique ='|is_unique[estudio.id_estudio]';
        }
        $this->form_validation->set_rules('txtid_estudio',' el id','required'.$unique);

        if(empty($_FILES['txtfile']['name'])){
             if($this->form_validation->run()){
                $data = array(
                    'id_estudio'        =>$id_estudio,
                    'dni_paciente'      =>$dni_paciente,
                    'email'             =>$email, 
                    'tipo_estudio'      =>$tipo_estudio,
                    'fecha_estudio'     =>$fecha_estudio                                       
                );
                $res = $this->mestudio->mupdateestudio($id_estudio,$data);
                if($res){
                    $this->session->set_flashdata('correcto','Se guardo correctamente');
                    redirect(base_url().'mantenimiento/cestudio');
                }else{
                    $this->session->set_flashdata('error','No se pudo actualizar el estudio');
                    redirect(base_url().'mantenimiento/cestudio/cedit'.$id_estudio);
                }                
            }else{
                $this->session->set_flashdata('error','No se pudo guardar el estudio');
                $this->cedit($id_estudio);
            }
        }else{
            $config['upload_path']='./uploads/files/';
            $config['allowed_types']='pdf';
            $this->load->library('upload', $config);

            if($this->upload->do_upload('txtfile')){
                $archivo         = $this->upload->data('file_name');
                $archivoActual   = $this->mestudio->midupdateestudio($id_estudio);
                unlink('./uploads/files/'.$archivoActual->archivo);
            }
            if($this->form_validation->run()){
                $data = array(
                    'id_estudio'        =>$id_estudio,
                    'dni_paciente'      =>$dni_paciente,
                    'tipo_estudio'      =>$tipo_estudio,
                    'fecha_estudio'     =>$fecha_estudio,
                    'email'             =>$email,
                    'archivo'           =>$archivo,                    
                );
                $res = $this->mestudio->mupdateestudio($id_estudio,$data);
                if($res){
                    $this->session->set_flashdata('correcto','Se guardo correctamente');
                    redirect(base_url().'mantenimiento/cestudio');
                }else{
                    $this->session->set_flashdata('error','No se pudo actualizar el estudio');
                    redirect(base_url().'mantenimiento/cestudio/cedit'.$id_estudio);
                }                
            }else{
                $this->session->set_flashdata('error','No se pudo guardar el estudio');
                $this->cedit($id_estudio);
            }  
        }   
    }

    public function enviarMail($id_estudio){
        $data = array($this->mestudio->midupdateestudio($id_estudio));
        $dni_paciente = $data[0]->dni_paciente;
        $archivo = $data[0]->archivo;        
        $correo = $data[0]->email;        
        $this->cUpdateEnvio($id_estudio);        
        $this->enviarEmail($correo,$archivo);
    }

    ///DATOS MODIFICADOS POR SEGURIDAD///
    public function enviarEmail($correo,$archivo){
        $config['protocol'] = "smtp";
        $config['smtp_host'] = '///////////////////////';
        $config['smtp_port'] = //////////////;
        $config['smtp_user'] = '////////////////////////'; //email desde donde se envía el email
        $config['smtp_pass'] = '///////////////////'; //password del email
        $config['mailtype'] = 'html';
        $config['charset'] = 'utf-8';
        $config['newline'] = "\r\n";
        $config['smtp_timeout'] = 30;
        $config['smtp_crypto'] = 'tls';
        $config['wordwrap'] = TRUE;
        $config['upload_path'] = './uploads/files/';
        $config['allowed_types'] = 'pdf';
        $config['max_size'] = '100000';
        $config['max_width']  = '2048';
        $config['max_height']  = '1536';
        $this->load->library('upload', $config);
        $this->upload->do_upload($archivo);
        $upload_data = $this->upload->data();
        $this->email->initialize($config);
        $this->email->from('/////////////////////////', 'Hospital Universitario');
        $this->email->to($correo);        
        $this->email->subject('Informe Cardiologíco - Hospital Universitario');
        $this->email->message('Se adjunta informe de estudio Cardiológico, tiene problemas para visualizar el mismo
        comuníquese al ///////////////. Por favor no responda este correo.');
        $this->email->attach($upload_data['full_path'].$archivo);
        var_dump( $upload_data['full_path'].$archivo );
        return $this->email->send();   
    }

    public function entregaEstudio($id_estudio){
        $data = array($this->mestudio->midupdateestudio($id_estudio));
        $dni_paciente = $data[0]->dni_paciente;
        $archivo = $data[0]->archivo;        
        $correo = $data[0]->email;
        $estado_envio='3';                
        $this->cUpdateEntrega($id_estudio);
    }
    
    public function cUpdateEntrega($id_estudio){
        $estudioActual = array($this->mestudio->midupdateestudio($id_estudio));
        
        
        $dni_paciente       = $estudioActual[0]->dni_paciente;
        $tipo_estudio       = $estudioActual[0]->tipo_estudio;
        $fecha_estudio      = $estudioActual[0]->fecha_estudio;
        $fecha_subida       = $estudioActual[0]->fecha_subida;
        $fecha_envio        = date('Y-m-d');
        $estado_envio       = 2;
        $archivo            = $estudioActual[0]->archivo;
        $idusuario_envio    = $this->session->userdata('user_name');
        $idusuario_subido   = $estudioActual[0]->idusuario_subido;
        $email              = $estudioActual[0]->email;        
        
                 $data = array(
                    'id_estudio'        =>$id_estudio,
                    'dni_paciente'      =>$dni_paciente,
                    'tipo_estudio'      =>$tipo_estudio,
                    'fecha_estudio'     =>$fecha_estudio,
                    'fecha_subida'      =>$fecha_subida,
                    'fecha_envio'       =>$fecha_envio,
                    'estado_envio'      =>$estado_envio,
                    'archivo'           =>$archivo,
                    'idusuario_envio'   =>$idusuario_envio,
                    'idusuario_subido'  =>$idusuario_subido,
                    'email'             =>$email
                );
                $res = $this->mestudio->mupdateestudio($id_estudio,$data);
                if($res){
                    $this->session->set_flashdata('correcto','Se guardo correctamente');
                    redirect(base_url().'mantenimiento/cestudio');
                }else{
                    $this->session->set_flashdata('error','No se pudo actualizar el estudio');
                    redirect(base_url().'mantenimiento/cestudio');
                }
        
    }

    public function cUpdateEnvio($id_estudio){
        $estudioActual = array($this->mestudio->midupdateestudio($id_estudio));
        
        
        $dni_paciente       = $estudioActual[0]->dni_paciente;
        $tipo_estudio       = $estudioActual[0]->tipo_estudio;
        $fecha_estudio      = $estudioActual[0]->fecha_estudio;
        $fecha_subida       = $estudioActual[0]->fecha_subida;
        $fecha_envio        = date('Y-m-d');
        $estado_envio       = 1;
        $archivo            = $estudioActual[0]->archivo;
        $idusuario_envio    = $this->session->userdata('user_name');
        $idusuario_subido   = $estudioActual[0]->idusuario_subido;
        $email              = $estudioActual[0]->email;        
        
                 $data = array(
                    'id_estudio'        =>$id_estudio,
                    'dni_paciente'      =>$dni_paciente,
                    'tipo_estudio'      =>$tipo_estudio,
                    'fecha_estudio'     =>$fecha_estudio,
                    'fecha_subida'      =>$fecha_subida,
                    'fecha_envio'       =>$fecha_envio,
                    'estado_envio'      =>$estado_envio,
                    'archivo'           =>$archivo,
                    'idusuario_envio'   =>$idusuario_envio,
                    'idusuario_subido'  =>$idusuario_subido,
                    'email'             =>$email
                );
                $res = $this->mestudio->mupdateestudio($id_estudio,$data);
                if($res){
                    $this->session->set_flashdata('correcto','Se guardo correctamente');
                    redirect(base_url().'mantenimiento/cestudio');
                }else{
                    $this->session->set_flashdata('error','No se pudo actualizar el estudio');
                    redirect(base_url().'mantenimiento/cestudio');
                }
        
    }    
}
?>
