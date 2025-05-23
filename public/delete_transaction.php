<?php
session_start();
require_once '../includes/transactions.php';
require_once '../config/config.php';

if (!isset($_SESSION['user_id'])) {
    header('location: index.php');
    exit;
}

if(isset($_GET['id'])) {
    $idTransaction = $_GET['id'];

    if(deleteTransaction($connection, $idTransaction)) {
        header('Location: transactions.php?success=Transaction successfully deleted.');
        exit();
    } else {
        echo "Error deleting transaction.";
    }
} else {
    echo "No transaction ID provided.";
}

?>