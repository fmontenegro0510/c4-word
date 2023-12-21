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

        $this->generarDocumentoWordTemplate($data);
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
        # cerramos el buffer 
        exit;
    }


    private function generarDocumentoWordTemplate($data){

        // // Ruta del template
        // $templatePath = FCPATH . 'assets/cv.dotm';

        // // Ruta de salida para el nuevo documento
        // $outputPath = WRITEPATH . 'cv_generado.docm';

        // // Copiar el template a la carpeta de salida
        // copy($templatePath, $outputPath);

        // // Cargar el documento template con PhpWord
        // $phpWord = \PhpOffice\PhpWord\IOFactory::load($outputPath);

        // // Reemplazar las variables en el documento
        // $phpWord->setValue('nombre', $data['nombre']);
        // $phpWord->setValue('apellido', $data['apellido']);
        // $phpWord->setValue('dni', $data['dni']);
        // $phpWord->setValue('categoria', $data['categoria']);

        // // Guardar el documento modificado
        // $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        // $objWriter->save($outputPath);

        // // Descargar el documento
        // $this->response->setDownload('cv_generado.docm')->setFilePath($outputPath)->setStatusCode(200);


        $templatePath = FCPATH . 'assets/cv.docx';

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);


        // Crear un nuevo objeto PhpWord
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $filename = 'test.docx';

        $templateProcessor->setValue('nombre', $data['nombre']);
        $templateProcessor->setValue('apellido', $data['apellido']);
        $templateProcessor->setValue('dni', $data['dni']);
        $templateProcessor->setValue('categoria', $data['categoria']);

        $templateProcessor->saveAs($filename);

        // send results to browser to download
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.$filename);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filename));
        flush();
        readfile($filename);
        unlink($filename); // deletes the temporary file
        exit;

        // // Cargar el contenido del template
        // $templateContent = file_get_contents($templatePath);

        // // Reemplazar las variables en el template con los valores recibidos
        // $templateContent = str_replace('{nombre}', $data['nombre'], $templateContent);
        // $templateContent = str_replace('{apellido}', $data['apellido'], $templateContent);
        // $templateContent = str_replace('{dni}', $data['dni'], $templateContent);
        // $templateContent = str_replace('{categoria}', $data['categoria'], $templateContent);

        // Cargar el contenido modificado en el objeto PhpWord
        // \PhpOffice\PhpWord\IOFactory::load($templateContent, 'HTML', $phpWord);

        // Ruta de salida para el nuevo documento
        // $outputPath = WRITEPATH . 'cv_generado.docm';

        // // Guardar el documento modificado
        // $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        // $objWriter->save($outputPath);

        // // Descargar el documento
        // return $this->response->download($outputPath, null, true);

    }




}
