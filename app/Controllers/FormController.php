<?php

namespace App\Controllers;

use App\Controllers\BaseController;


use PhpOffice\PhpWord\Style\Language;

class FormController extends BaseController
{

     public function index()
    {
        return view('formulario');
    }

    public function generarDocumento()
    {
        $validationRules = [
            'nombre' => 'required|min_length[3]|max_length[50]',
            'apellido' => 'required|min_length[3]|max_length[50]',
            'dni' => 'required|numeric|exact_length[8]',
            'categoria' => 'required|min_length[3]|max_length[50]',
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->to('/form')->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'apellido' => $this->request->getPost('apellido'),
            'dni' => $this->request->getPost('dni'),
            'categoria' => $this->request->getPost('categoria'),
        ];

        $this->generarDocumentoWord($data);
    }

    private function generarDocumentoWord($data)
    {

        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        $propiedades = $phpWord->getDocInfo();
        $propiedades->setCreator("Francisco Montenegro");
        $propiedades->setCompany("PangoSoft");
        $propiedades->setTitle("PHPDOCX");
        $propiedades->setDescription("CodeIgniter4 + Word");
        $propiedades->setCategory("Tutoriales");
        $propiedades->setLastModifiedBy("Francisco Montenegro");
        $propiedades->setSubject("Asunto");
        $propiedades->setKeywords("documento, php, word");

        

        # Para que no diga que se abre en modo de compatibilidad
        $phpWord->getCompatibility()->setOoxmlVersion(15);
        # Idioma español de México
        $phpWord->getSettings()->setThemeFontLang(new Language("ES-AR"));

        $section = $phpWord->addSection();
        $section->addText('Nombre: ' . $data['nombre']);
        $section->addText('Apellido: ' . $data['apellido']);
        $section->addText('DNI: ' . $data['dni']);
        $section->addText('Categoría: ' . $data['categoria']);

        // $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        // $objWriter->save('archivo.docx');

        $nombre = $data['nombre'] . '-' .  $data['apellido'].'.docx';

        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="' . $nombre . '"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, "Word2007");
        # limpiamos el buffer
        ob_clean();
        # Y lo enviamos a php://output
        $objWriter->save("php://output");
        //cerramos 
        exit;

//       $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, "Word2007");
//      $objWriter->save("Pepinillo.docx");
    
    }
}
