<?php
class AlunoValidator
{
  private $habilitarValidacao = true;

  private $alunosApiUrl;

  public function __construct()
  {
    $this->alunosApiUrl = 'https://alunosapi.infinityfreeapp.com/api/alunos';
  }

  public function exists($matricula)
  {
    if (!$this->habilitarValidacao) {
      return true;
    }

    $url = $this->alunosApiUrl . '/' . intval($matricula);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      'Accept: application/json',
      'User-Agent: HistoricoAPI/1.0'
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($curlError) {
      error_log("AlunoValidator: cURL error for matricula {$matricula}: {$curlError}");
      return false;
    }

    return $httpCode === 200;
  }

  public function getAluno($matricula)
  {
    $url = $this->alunosApiUrl . '/' . intval($matricula);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      'Accept: application/json',
      'User-Agent: HistoricoAPI/1.0'
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($curlError) {
      error_log("AlunoValidator: JSON error for matricula {$matricula}: {$curlError}");
      return null;
    }

    if ($httpCode === 200 && $response) {
      $data = json_decode($response, true);

      if (json_last_error() === JSON_ERROR_NONE) {
        return $data;
      } else {
        error_log("AlunoValidator: JSON decode error for matricula {$matricula}");
      }
    }

    return null;
  }

  public function isValidFormat($matricula)
  {
    return is_numeric($matricula) && intval($matricula) > 0 && intval($matricula) == $matricula;
  }
}
?>