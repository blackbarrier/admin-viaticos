<?php

namespace App\Controller;

use App\Form\ForgotPassType;
use App\Repository\UsuarioRepository;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Validator\Constraints\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\TooManyLoginAttemptsAuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Serializer\Normalizer\CustomNormalizer;

class LoginController extends BaseController
{
    public $repo_users;

    public function __construct( UsuarioRepository $usuarioRepository) {
        $this->repo_users = $usuarioRepository;
    }


    #[Route(path: '/', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        if($this->getUser()){
            
            return $this->redirectToRoute("app_index");
        }
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        $error = $this->customizarError($error);        
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        
        return $this->render("layoutLogin.html.twig",[
            'last_username' => $lastUsername,
             'error' => $error
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(Request $request){
        $request->getSession()->invalidate();
        return $this->redirectToRoute("app_login");
    }

    #[Route('/perfil', name: 'app_usuario_pefil', methods: ['GET', 'POST'])]
    public function perfil(Request $request): Response {
        $usuario= $this->repo_users->findOneBy(["email" => $this->getUser()->getUserIdentifier()]);
        return $this->render('usuario/perfil.html.twig', [
            'usuario' => $usuario,
        ]);
    }

    #[Route('/contraseña_olvidada', name: 'app_usuario_contraseña_olvidada', methods: ['GET', 'POST'])]
    public function reestablecer_password(Request $request, UsuarioRepository $usuarioRepository): Response
    {
        $form = $this->createForm(ForgotPassType::class);        
        $form->handleRequest($request);         

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $email = $data['email'];

            //Alternativa reestabler por DNI
            $usuario = $usuarioRepository->findOneBy(['email'=>$email]);
            $usuario->setPassword($usuario->getDni());


            //Alternativa Mail

            // if (mail($to, $subject, $message, $headers)) {
            //     return new Response('Correo enviado con éxito.');
                
            // } else {
            //     return new Response('No se pudo enviar el correo.');
            // }


            //Verificar este Addflash/////////////////////////////
            $this->addFlash("success", "Contraseña reestablecida. Verifique su correo ".$email);
            return $this->redirectToRoute('app_login');     
        };       

        // $usuario = $this->repo_users->findOneBy(["email" => $this->getUser()->getUserIdentifier()]);
        return $this->render('contrasenaOlvidada.html.twig', [
            'form' => $form
            // 'usuario' => $usuario,
        ]);
    }

    

    private function customizarError($exceptionError) {       
        
        if($exceptionError == null) return $exceptionError;

        if($exceptionError instanceof TooManyLoginAttemptsAuthenticationException) {
            return "Demasiados intentons de login";
        }
        if($exceptionError instanceof BadCredentialsException) {
            if($exceptionError->getMessage() == "Bad credentials.")
                return "Usuario inválido";
            if($exceptionError->getMessage() == "The presented password is invalid.")
                return "Contraseña inválida";
        }
        return "Error no especificado";
    }


}
