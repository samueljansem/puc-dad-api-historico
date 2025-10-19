<?php
require_once 'database.php';

class HistoricoController
{
  private $db;

  public function __construct()
  {
    global $conn;
    $this->db = $conn;
  }

  /**
   * GET /api/alunos/{matricula}/historico
   */
  public function getHistorico($matricula)
  {
    try {
      $stmt = $this->db->prepare("SELECT * FROM HistoricoEscolar WHERE estudante = :matricula");
      $stmt->bindParam(':matricula', $matricula);
      $stmt->execute();
      $historico = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$historico) {
        echo json_encode(['error' => 'Historico not found']);
      } else {
        echo json_encode($historico);
      }

    } catch (PDOException $e) {
      echo json_encode(['error' => $e->getMessage()]);
    }
  }

  /**
   * POST /api/alunos/{matricula}/historico
   */
  public function createHistorico($matricula)
  {
    if (isset($_POST['tipo_ensino'])) {
      $tipo_ensino = $_POST['tipo_ensino'];
      $ano_desde_ensino_medio = isset($_POST['ano_desde_ensino_medio']) ? $_POST['ano_desde_ensino_medio'] : null;
      $fez_cursinho = isset($_POST['fez_cursinho']) ? $_POST['fez_cursinho'] : null;
      $possui_bolsa = isset($_POST['possui_bolsa']) ? $_POST['possui_bolsa'] : null;

      try {
        $stmt = $this->db->prepare('INSERT INTO HistoricoEscolar (estudante, tipo_ensino, ano_desde_ensino_medio, fez_cursinho, possui_bolsa) VALUES (:matricula, :tipo_ensino, :ano_desde_ensino_medio, :fez_cursinho, :possui_bolsa)');
        $stmt->bindParam(':matricula', $matricula);
        $stmt->bindParam(':tipo_ensino', $tipo_ensino);
        $stmt->bindParam(':ano_desde_ensino_medio', $ano_desde_ensino_medio);
        $stmt->bindParam(':fez_cursinho', $fez_cursinho);
        $stmt->bindParam(':possui_bolsa', $possui_bolsa);
        $stmt->execute();
        echo json_encode(['success' => true]);
      } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
      }
    } else {
      echo json_encode(['error' => 'O tipo de ensino é obrigatório']);
    }
  }

  /**
   * PATCH /api/alunos/{matricula}/historico
   */
  public function updateHistorico($matricula)
  {
    if ((isset($_POST['matricula'])) && (isset($_POST['tipo_ensino']))) {
      $tipo_ensino = $_POST['tipo_ensino'];
      $ano_desde_ensino_medio = isset($_POST['ano_desde_ensino_medio']) ? $_POST['ano_desde_ensino_medio'] : null;
      $fez_cursinho = isset($_POST['fez_cursinho']) ? $_POST['fez_cursinho'] : null;
      $possui_bolsa = isset($_POST['possui_bolsa']) ? $_POST['possui_bolsa'] : null;

      try {
        $stmt = $this->db->prepare('UPDATE HistoricoEscolar SET tipo_ensino = :tipo_ensino, ano_desde_ensino_medio = :ano_desde_ensino_medio, fez_cursinho = :fez_cursinho, possui_bolsa = :possui_bolsa WHERE estudante = :matricula');
        $stmt->bindParam(':matricula', $matricula);
        $stmt->bindParam(':tipo_ensino', $tipo_ensino);
        $stmt->bindParam(':ano_desde_ensino_medio', $ano_desde_ensino_medio);
        $stmt->bindParam(':fez_cursinho', $fez_cursinho);
        $stmt->bindParam(':possui_bolsa', $possui_bolsa);
        $stmt->execute();
        echo json_encode(['success' => true]);
      } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
      }
    } else {
      echo json_encode(['error' => 'A matrícula e tipo de ensino são obrigatórios']);
    }
  }

  /**
   * DELETE /api/alunos/{matricula}/historico
   */
  public function deleteHistorico($matricula)
  {
    if (isset($_POST['matricula'])) {
      try {
        $stmt = $this->db->prepare('DELETE FROM HistoricoEscolar WHERE estudante = :matricula');
        $stmt->bindParam(':matricula', $matricula);
        $stmt->execute();
        echo json_encode(['success' => true]);
      } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
      }
    } else {
      echo json_encode(['error' => 'A matrícula é obrigatória']);
    }
  }
}
?>