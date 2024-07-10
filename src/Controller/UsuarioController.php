<?php

namespace App\Controller;

use Exception;
use App\Entity\Usuario;
use App\Form\UsuarioType;
use App\Repository\DependenciaReparticionRepository;
use App\Repository\RolRepository;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

#[Route('/usuario')]
class UsuarioController extends BaseController
{
    public $repo_users;
    public $repo_roles;

    public function __construct(UsuarioRepository $usuarioRepository, RolRepository $rolRepository) {
        $this->repo_users = $usuarioRepository;
        $this->repo_roles = $rolRepository;
    }

    #[Route('/', name: 'app_usuario_index', methods: ['GET'])]
    public function index(UsuarioRepository $usuarioRepository): Response {
        return $this->render('usuario/index.html.twig', [
            'usuarios' => $usuarioRepository->findBy(["borrado" => 0]),
        ]);
      
    }

    #[Route('/nuevo', name: 'app_usuario_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DependenciaReparticionRepository $dependenciaReparticionRepository): Response {  
        $usuario = new Usuario();
        $userReparticion = $this->getUser()->getReparticion();        
        $dependencias = $dependenciaReparticionRepository->obtenerporReparticion($userReparticion);
        $form = $this->createForm(UsuarioType::class, $usuario);  
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            date_default_timezone_set("America/Argentina/Buenos_Aires");
            $usuario->setNombre(strtoupper($usuario->getNombre()));
            $usuario->setApellido(strtoupper($usuario->getApellido()));
            $usuario->setActivo(1);
            $usuario->setBorrado(0);
            $hoy= new \DateTime();
            $usuario->setFechaAlta($hoy);
            $newpass = PASSWORD_HASH($usuario->getDni(),PASSWORD_DEFAULT);
            $usuario->setPassword($newpass);
            $usuario->setReparticion($userReparticion);
            try{
                $userEncontrado = $this->repo_users->findOneBy(["email" => $usuario->getEmail(),"borrado" => 0]);
                if(!$userEncontrado){

                    if (strpos($usuario->getCuil(), $usuario->getDni()) !== false) {
                        $this->repo_users->agregar($usuario,true);
                        $this->addFlash("success","El nuevo usuario fue creado con éxito.");
                        return $this->redirectToRoute('app_usuario_index', [], Response::HTTP_SEE_OTHER);
                    } else {
                        $this->addFlash("error", "Campo DNI y campo CUIL deben ser coincidentes.");
                        return $this->redirectToRoute("app_usuario_new");                        
                    }       

                }else{
                    $this->addFlash("error","El email indicado ya pertenece a otro usuario, por favor intente con un email diferente.");
                    $this->redirectToRoute("app_usuario_index");
                }    

            }catch(Exception $e){
                $this->addFlash("error",$e->getMessage());
                //dd($e);
            }
        }
        return $this->render('usuario/new.html.twig', [
            'form' => $form->createView(),  // Render the form here
            'dependencias' => $dependencias
        ]);            
    }


    #[Route('/{id}/detalles', name: 'app_usuario_show', methods: ['GET'])]
    public function show(Usuario $usuario): Response {
        return $this->render('usuario/show.html.twig', [
            'usuario' => $usuario,
        ]);        
    }

    #[Route('/{id}/editar', name: 'app_usuario_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Usuario $usuario): Response {
        $form = $this->createForm(UsuarioType::class, $usuario);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (strpos($usuario->getCuil(), $usuario->getDni()) !== false) {
                $data= $form->getData();
                $this->repo_users->agregar($usuario,true);
                $this->addFlash('success', 'El usuario ha sido editado correctamente.');   
                return $this->redirectToRoute('app_usuario_index', [], Response::HTTP_SEE_OTHER);
            } else {
                $this->addFlash("error", "Campo DNI y campo CUIL deben ser coincidentes.");
                $this->redirectToRoute("app_usuario_edit",["id" => $usuario->getId()]);
            }       
        }
        return $this->render('usuario/edit.html.twig', [
            'usuario' => $usuario,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/activar-desactivar', name: 'app_usuario_cambiar_activo', methods: ['GET', 'POST'])]
    public function cambiar_activo(Request $request, Usuario $usuario): Response {
            $activo = $usuario->getActivo();
            switch($activo){
                case 0:
                    $usuario->setActivo(1);
                    break;
                case 1:
                    $usuario->setActivo(0);
                    break;
                }
            // dd("Cambiado de estado");
            $this->repo_users->agregar($usuario,true);
            $this->addFlash("success","Se cambió el estado de actividad del usuario correctamente.");
            return $this->redirectToRoute('app_usuario_index', [], Response::HTTP_SEE_OTHER);
    }
    

   

    #[Route('/{id}/borrar', name: 'app_usuario_delete', methods: ['GET','POST'])]
    public function delete(Usuario $usuario): Response {
        $usuario->setBorrado(1);
        $this->repo_users->agregar($usuario,true);
        $this->addFlash("success","Se borró al usuario correctamente.");
        return $this->redirectToRoute('app_usuario_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route("/checkIfExists", name: 'app_ajax_check_existance', methods:['POST'])]
    public function ajax_check_email():Response{
       $email= $_POST["email"];
       $borrado = 0;
       $resultado = $this->repo_users->userExists($email,$borrado);
       return $this->json(["existe" => $resultado],Response::HTTP_OK);
    }

    #[Route("/password-reset/{id}", name:"app_pass_reset", methods:["GET","POST"])]
    public function ajax_reset_pass($id){    
        try{
            $usuario = $this->repo_users->findOneBy(["id" => $id]);
            if($usuario->getDni() == 0 || $usuario->getDni() == "" || $usuario->getDni() == null){
                $contraHash = password_hash("123456",PASSWORD_DEFAULT);
            }else{
                $contraHash= password_hash("".$usuario->getDni(),PASSWORD_DEFAULT);
            }
            $usuario->setPassword($contraHash);
            $this->repo_users->agregar($usuario,true);
            $this->addFlash("success","Contraseña de usuario seleccionado reiniciada correctamente.");
            return $this->redirectToRoute('app_usuario_index', [], Response::HTTP_SEE_OTHER);
        }catch(Exception $e){
            return $this->json([
                "resultado" => $e->getMessage(),
            ],Response::HTTP_CONFLICT);
        }        
    }


    #[Route("/perfil/cambiar-password", name:"app_change_pass", methods:['GET','POST'])]
    public function ajax_change_pass():Response{
        if(isset($_POST["contraActual"])){
            $contraActual =$_POST["contraActual"];
        }else{
            $contraActual = null;
        }
        if(isset($_POST["nuevaContra"])){
            $nuevaContra =  $_POST["nuevaContra"];
        }else{
            $nuevaContra = null;
        }
        if($contraActual == null || $nuevaContra == null){
            return $this->json([
                "resultado" => "Hubo un error al recuperar los datos de los datos ingresados."
            ],Response::HTTP_ACCEPTED);
        }        
        try{
            $user = $this->repo_users->findOneBy(["email" => $this->getUser()->getUserIdentifier()]);
            if(password_verify($contraActual, $user->getPassword())){
                $contraHash= password_hash($nuevaContra,PASSWORD_DEFAULT);
                $user->setPassword($contraHash);
                $this->repo_users->agregar($user,true);
                return $this->json([
                    "resultado" => "La contraseña fue cambiada con éxito, deberas usarla a partir de tu próxima sesión."
                ],Response::HTTP_ACCEPTED); 
            }else{
                return $this->json([
                    "resultado" => "La contraseña actual es incorrecta. Cambio de contraseña cancelado."
                ],Response::HTTP_OK);
            }
        }catch(Exception $e){
            return $this->json([
                "resultado" => $e->getMessage(),
            ],Response::HTTP_CONFLICT);
        }
    }

}


