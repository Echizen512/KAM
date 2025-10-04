<!DOCTYPE html>
<html lagn="es">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">

<head>
    <title>KAM</title>
    <link rel="stylesheet" href="./Assets/CSS/Personal.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    


</head>

<style>
    #animated-bg {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 0;
        pointer-events: none;
    }

    .z-1 {
        z-index: 1;
    }

    .container-fluid {
        position: relative;
        z-index: 2;
        background-color: #f8f9fa;
        backdrop-filter: none;
    }

    .floating-button {
        background-color: #2378b2;
        opacity: 0.6;
        border: none;
        border-radius: 50%;
        width: 60px;
        height: 60px;
        position: fixed;
        bottom: 35px;
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transition: opacity 0.3s ease;
        z-index: 10;
    }

    .right-button {
        right: 20px;
    }

    .left-button {
        left: 20px;
    }

    .floating-button:hover {
        opacity: 0.85;
    }

    .floating-button .hover-message {
        display: none;
        position: absolute;
        bottom: 75px;
        background-color: #2378b2;
        color: white;
        padding: 6px 10px;
        border-radius: 6px;
        font-size: 0.8rem;
        white-space: nowrap;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    }

    .right-button .hover-message {
        right: 0;
    }

    .left-button .hover-message {
        left: 0;
    }

    .floating-button:hover .hover-message {
        display: block;
    }
</style>