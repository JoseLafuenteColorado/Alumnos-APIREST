<?php
namespace App\Controllers;

require_once ('../app/Routes/AlumnoGateway.php');

use App\Routes\AlumnoGateway;

class AlumnoController {

    private $db;
    private $requestMethod;
    private $alumnoId;

    private $alumnoGateway;

    public function __construct($db, $requestMethod, $alumnoId) {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->alumnoId = $alumnoId;

        $this->alumnoGateway = new AlumnoGateway($db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->alumnoId) {
                    $response = $this->getAlumno($this->alumnoId);
                } else {
                    $response = $this->getAllAlumnos();
                };
                break;
            case 'POST':
                $response = $this->createAlumnoFromRequest();
                break;
            case 'PATCH':
                $response = $this->updateAlumnoFromRequest($this->alumnoId);
                break;
            case 'DELETE':
                $response = $this->deleteAlumno($this->alumnoId);
                break;
            case 'PUT':
                $response = $this->updateImageAlumnoFromRequest($this->alumnoId);
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    private function getAllAlumnos()
    {
        $result = $this->alumnoGateway->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function getAlumno($alumnoId)
    {
        $result = $this->alumnoGateway->find($alumnoId);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function createAlumnoFromRequest()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validateAlumno($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->alumnoGateway->insert($input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = null;
        return $response;
    }

    private function updateAlumnoFromRequest($alumnoId)
    {
        $result = $this->alumnoGateway->find($alumnoId);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validateAlumno($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->alumnoGateway->update($alumnoId, $input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }
    private function updateImageAlumnoFromRequest($alumnoId)
    {
        $result = $this->alumnoGateway->find($alumnoId);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validateImageAlumno($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->alumnoGateway->update($alumnoId, $input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    private function deleteAlumno($alumnoId)
    {
        $result = $this->alumnoGateway->find($alumnoId);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $this->alumnoGateway->delete($alumnoId);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    private function validateAlumno($input)
    {
        if (! isset($input['dni'])) {
            return false;
        }
        if (! isset($input['nombre'])) {
            return false;
        }
        if (! isset($input['apellidos'])) {
            return false;
        }
        if (! isset($input['telefono'])) {
            return false;
        }

        // if (! isset($input['foto'])) {
        //     return false;
        // }
        return true;
    }
    private function validateImageAlumno($input)
    {
        if (! isset($input['foto'])) {
            return false;
        }
        return true;
    }

    private function unprocessableEntityResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode([
            'error' => 'Invalid input'
        ]);
        return $response;
    }

    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }
}

?>