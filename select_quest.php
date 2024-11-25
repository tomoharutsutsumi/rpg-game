<?php
session_start();
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['qid'])) {
    $qid = $_POST['qid'];

    // Obtener los detalles de la quest seleccionada
    $query = "SELECT IntroText FROM quest WHERE QID = :qid";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['qid' => $qid]);
    $quest = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($quest) {
        unset($_SESSION['game_over']);
        unset($_SESSION['next_turn']);
        unset($_SESSION['player_health']);

        $_SESSION['intro_text'] = $quest['IntroText'];
        $_SESSION['selected_quest'] = $qid;
        header("Location: quest_intro.php");
        exit();
    } else {
        echo "Quest not found.";
    }
}
?>
