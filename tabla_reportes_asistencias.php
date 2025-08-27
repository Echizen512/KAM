<?php
session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario']) || !isset($_SESSION['correo'])) {
    echo "<!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Acceso denegado',
                text: 'No puede acceder al sistema sin haber iniciado sesión',
                confirmButtonText: 'Aceptar'
            }).then(() => {
                window.location.href = 'Login.php';
            });
        </script>
    </body>
    </html>";
    exit; // Detiene la ejecución del resto del código PHP.
}

// Obtén los datos del usuario desde la sesión
$usuario = $_SESSION['usuario'];
$correo = $_SESSION['correo'];
?>
    <!DOCTYPE html>
<html>
<head>
    <title>KAM</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
</head>
<body>
    <style>
        body {
            background: url(FONDO3.jpg) no-repeat;
            background-size: 110%;
            margin: auto;
            background-attachment: fixed;
            background-repeat: no-repeat;
            background-position: top center;
            font-family: sans-serif;
        }

     
/*estilo de barra de busqueda*/
.search-container{

display:flex;
position: relative;
justify-content: left;
align-items: left;
border:1 px solid #2378b6;
border-radius:10px;
overflow:hidden;
left:77%;
margin-top: 3%;
}


.search-container input[type="text"]{

display:flex;
padding:12px 20px;
border:2px solid rgb(35,120,182);
outline:2px;
border-radius:25px;
}

.search-icon{

 position:absolute;
 top:60%;
 left:80%;
 transform:translateY(-50%);
 pointer-events: none; 


}

/*posicion logos*/

.left{
display: flex;
position: relative;
justify-content: left;
top: -10%;

}

 .boot{
width: 5px;
height: 5px;
          }
 
</style>



     <div class ="left">
    <div class="search-container">
    <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Buscar en la tabla...">
    <div class="search-icon">

    <img src="lupa.png" width="25" height="15"alt="#">

    </div></div></div>
<script>

        document.getElementById('searchInput').addEventListener('keyup', function() {
    const filter = document.getElementById('searchInput').value.toUpperCase();
    const table = document.getElementById('miTabla');
    const tr = table.getElementsByTagName('tr');
    
    for (let i = 1; i < tr.length; i++) {
        tr[i].style.display = 'none';
        const td = tr[i].getElementsByTagName('td');
        
        for (let j = 0; j < td.length; j++) {
            if (td[j]) {
                if (td[j].innerHTML.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = '';
                    break;
                }
            }
        }
    }
});
</script>
   
    <script>
        document.querySelectorAll('.logo-container').forEach(container => {
            const logo = container.querySelector('.logo');
            const hoverMessage = container.querySelector('.hover-message');

            logo.addEventListener('mouseenter', () => {
                hoverMessage.style.display = 'block';
            });

            logo.addEventListener('mouseleave', () => {
                hoverMessage.style.display = 'none';
            });
        });
    </script>

<style>
    .logo-container {
        position: absolute;
        top: 40px;
        left: 8px;
        display: flex;
        align-items: center;
    }

    .fixed-message {
        font-size: 12px;
        background-color: rgba(51, 51, 51, 0.9);
        color: white;
        padding: 5px;
        border-radius: 5px;
        margin-left: 15px; /* Ajusta este valor según sea necesario */
        display: inline-block; /* Asegura que se alinee adecuadamente */
    }

    h1 {
        text-align: center;
        color: #2378b2;
    }

    .letra {
        margin-top: -4%;
    }
</style>

<div class="logo-container">
    <a href="Inicio.php">
        <img src="inicio.png" width="40" height="30" alt="perfil">
    </a>
    <div class="fixed-message">Principal / Reportes</div>
</div>

     
    </div>

       
<div class="container">
    <div class="logos">
        <div class="hover-container3">
            <a href="#" id="userProfileButton">
                <img src="usuario.png" alt="Perfil">
                <div class="hover-message3">Perfil</div>
            </a>
        </div>
    </div>
</div>
<br>
<div id="userProfileModal" class="modal">
    <div class="modalt-contente">
        <span class="close" id="closeProfile">&times;</span>
        <div align="left">
            
<div class="p">
            <h2>Perfil de Usuario</h2>
            <p><strong>Nombre de Usuario:</strong> <?php echo htmlspecialchars($usuario); ?></p>
            <p><strong>Correo Electrónico:</strong> <?php echo htmlspecialchars($correo); ?></p>
   
            </div>
        </div>
    </div>
</div>
    <div class="letra">
<h1>Gestión De Reportes Asistencia</h1>
</div>
    <style>
          table {
            width: 98%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 4px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        td{
            font-size: 15px;
            padding: 4px;
        }
        th {
            background-color: #2378b6;
            color: white;
            padding: 8px 16px;
            margin: 1px 0;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }
    button {
      background-color: #2378b6;
      color: white;
      padding: 10px 10px;
      border-radius: 5px;
      border:none;
      font-size: 12px;
      cursor: pointer;
      transition: background-color 0.3s;
      right: 800%;
    }

    button:hover {
      background-color: #41a7f5;
    }

    .right{
margin-left: 70%;

    }
    </style>
</head>
<body>
<br>
<?php
// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "base_kam");
if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}

// Consulta para obtener datos únicos
$sql = "SELECT DISTINCT p.nombre_personal, p.apellido_personal, p.cedula_personal 
        FROM persona p 
        LEFT JOIN asistencia a 
        ON p.cedula_personal = a.cedula_personal";
$result = $conn->query($sql);
?>
<div class align="center">
    <table id="miTabla">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Cédula</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Mostrar los datos en la tabla
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>" . $row["nombre_personal"] . "</td>
                        <td>" . $row["apellido_personal"] . "</td>
                        <td>" . $row["cedula_personal"] . "</td>
                 
                        
                        <td>
                            <a href='#' class='descargar-reporte tooltip' data-cedula='" . $row["cedula_personal"] . "'>
                                <img src='descargar.png' alt='Descargar' width='25' height='22'>
                                <span class='tooltiptext'>Descargar reporte</span>
                            </a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No hay registros</td></tr>";
            }
            $conn->close();
            ?>
        </tbody>
    </table>
</div>
<style>
                        .tooltip {
                          position: relative;
                          display: inline-block;
                        }
                        
                        .tooltip .tooltiptext {
                          visibility: hidden;
                          width: 120px;
                          background-color: #333;
                          opacity:1%;
                          color: #fff;
                          font-size:12px;
                          text-align: center;
                          border-radius: 6px;
                          padding: 4px 0;
                          position: absolute;
                          z-index: 1;
                          bottom: 125%;
                          left: 50%;
                          margin-left: -60px;
                          opacity: 0;
                          transition: opacity 0.3s;
                        }
                        
                        .tooltip .tooltiptext::after {
                          content: "";
                          position: absolute;
                          top: 100%;
                          left: 50%;
                          margin-left: -10px;
                          border-width: 5px;
                          border-style: solid;
                          border-color: black transparent transparent transparent;
                        }
                        
                        .tooltip:hover .tooltiptext {
                          visibility: visible;
                          opacity: 5;
                        }
                        </style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Manejo de eventos con SweetAlert2
    document.querySelectorAll('.descargar-reporte').forEach(item => {
        item.addEventListener('click', function (e) {
            e.preventDefault();
            const cedula = this.getAttribute('data-cedula');
            abrirModal(cedula);
        });
    });

    function abrirModal(cedula) {
        Swal.fire({
            title: 'Selecciona el tipo de reporte',
            input: 'select',
            inputOptions: {
                'diario': 'Reporte Diario',
                'general': 'Reporte General',
            },
            inputPlaceholder: 'Selecciona un tipo de reporte',
            showCancelButton: true,
            inputValidator: (value) => {
                return !value && 'Debes seleccionar un tipo de reporte';
            }
        }).then((result) => {
            if (result.isConfirmed) {
                if (result.value === 'general') {
                    Swal.fire({
                        title: 'Selecciona el rango de fechas',
                        html: `
                            <div style="display: flex; flex-direction: column; align-items: center;">
                                <label for="fechaInicio" style="margin-bottom: 5px; font-weight: bold;">Fecha de inicio:</label>
                                <input id="fechaInicio" type="date" class="swal2-input" style="width: 80%; margin-bottom: 10px;">
                                <label for="fechaFin" style="margin-bottom: 5px; font-weight: bold;">Fecha de fin:</label>
                                <input id="fechaFin" type="date" class="swal2-input" style="width: 80%;">
                            </div>
                        `,
                        showCancelButton: true,
                        preConfirm: () => {
                            const fechaInicio = document.getElementById('fechaInicio').value;
                            const fechaFin = document.getElementById('fechaFin').value;
                            if (!fechaInicio || !fechaFin) {
                                Swal.showValidationMessage('Debes seleccionar ambas fechas');
                            }
                            return { fechaInicio, fechaFin };
                        }
                    }).then((rangoResult) => {
                        if (rangoResult.isConfirmed) {
                            // Llama a generarReporte con el rango de fechas
                            generarReporte('general', cedula, rangoResult.value.fechaInicio, rangoResult.value.fechaFin);
                        }
                    });
                } else {
                    // Generar reporte diario directamente
                    generarReporte('diario', cedula);
                }
            }
        });
    }

    function generarReporte(tipo, cedula, fechaInicio = '', fechaFin = '') {
        // Construir la URL con los parámetros adecuados
        let url = `generar_reporte.php?tipo=${tipo}&cedula=${cedula}`;
        if (tipo === 'general') {
            url += `&fechaInicio=${encodeURIComponent(fechaInicio)}&fechaFin=${encodeURIComponent(fechaFin)}`;
        }

        // Genera el reporte solicitando al servidor y descargando el archivo
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.exito) {
                    Swal.fire({
                        title: 'Reporte generado correctamente',
                        icon: 'success',
                        showConfirmButton: true,
                    }).then(() => {
                        // Redirigir al archivo generado para descargarlo directamente
                        const downloadLink = document.createElement('a');
                        downloadLink.href = data.url_descarga;
                        downloadLink.download = `reporte_${tipo}_${cedula}.pdf`;
                        downloadLink.click();
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: data.mensaje,
                        icon: 'error',
                    });
                }
            })
            .catch(err => {
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un problema al generar el reporte.',
                    icon: 'error',
                });
                console.error('Error:', err);
            });
    }
</script>


<br>
<style>
    .left {
        margin-left: 2%;
    }

    .page-number {
        display: inline-block;
        padding: 5px 10px;
        margin: 0 2px;
        border: 1px solid #ddd;
        border-radius: 5px;
        cursor: pointer;
    }

    .page-number.active {
        background-color: #2378b6;
        color: white;
        border-color: #2378b6;
    }
</style>

<div id="pagination" style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px;">
    <div style="display: flex; align-items: center;">
        <button style="margin-right: 10px;" onclick="prevPage()">Anterior</button>
        <button onclick="nextPage()">Siguiente</button>
    </div>
    <div id="pageNumbers" style="display: flex; align-items: center; margin-left: auto; margin-right: 10px;"></div>
</div>
<script>
    var currentPage = 1;
    var rowsPerPage = 11;

    function showPage(page) {
        var table = document.getElementById("miTabla");
        var rows = table.getElementsByTagName("tr");
        var start = (page - 1) * rowsPerPage + 1;
        var end = start + rowsPerPage;

        for (var i = 1; i < rows.length; i++) {
            if (i >= start && i < end) {
                rows[i].style.display = "";
            } else {
                rows[i].style.display = "none";
            }
        }
        updatePageNumbers();
    }

    function nextPage() {
        var table = document.getElementById("miTabla");
        var rows = table.getElementsByTagName("tr");
        if (currentPage * rowsPerPage < rows.length - 1) {
            currentPage++;
            showPage(currentPage);
        }
    }

    function prevPage() {
        if (currentPage > 1) {
            currentPage--;
            showPage(currentPage);
        }
    }

    function updatePageNumbers() {
        var pageNumbers = document.getElementById("pageNumbers");
        pageNumbers.innerHTML = "";
        var table = document.getElementById("miTabla");
        var rows = table.getElementsByTagName("tr");
        var totalPages = Math.ceil((rows.length - 1) / rowsPerPage);

        for (var i = 1; i <= totalPages; i++) {
            var pageNumber = document.createElement("div");
            pageNumber.className = "page-number" + (i === currentPage ? " active" : "");
            pageNumber.innerText = i;
            pageNumber.setAttribute("onclick", "showPage(" + i + ")");
            pageNumbers.appendChild(pageNumber);
        }
    }

    // Mostrar la primera página
    showPage(currentPage);
</script>

<style>
    
    .hover-message3 {
              display: none;
              background-color: rgba(51, 51, 51, 0.6); /* Fondo #333 con opacidad de 60% */
              color: white; /* Letras blancas */
              font-size: 15px;
              border-radius: 5px;
              padding: 4px;
              position: absolute;
              top: 50px; /* Ajustar según sea necesario */
              left: 40%;
              transform: translateX(-50%);
              z-index: 1;
              opacity: 0.9;
          }
  
          
          .hover-container3:hover .hover-message3 {
              display: block;
          }
  
          .container {
              margin: 0em 0em 0em 0em;
          }
  
              .logos {
              position: absolute;
              margin-top:-40px;
              left: 1290px;
              display: flex;
              gap: 20px; /* Espacio entre los logos */
          }
  .hover-container {
              position: relative;
              cursor: pointer;
              z-index: 1;
          }
          .hover-container img {
              width: 40px;
              height: 40px;
              transition: transform 0.3s ease;
          }
          .hover-container:hover img {
              transform: scale(1.2);
              z-index: 2;
          }
        .hover-container3 {
              position: relative;
              display: inline-block;
          }
          
  
  
  
  
   .menu-container {
        display: none;
        position: absolute;
        top: 80px;
        background-color: white;
        border: 1px solid #ccc;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        z-index: 1;
        padding: 10px;
      }
  
      .menu-option {
        padding: 20px;
        cursor: pointer;
      }
  
      .menu-option:hover {
        background-color: #f0f0f0;
      }
  
  
  
          .logos a {
              display: inline-block;
          }
  
          .logos img {
              width: 40px;
              height: 40px;
          }
  
          .modalt {
              display: none;
              position: fixed;
              z-index: 1;
              left: 0;
              top: 1px;
              width: 100%;
              height: 100%;
              overflow: auto;
              
              padding-top: 20px;
          }
  
          .modalt-contente {
    background-color: #fff;
    opacity: 90%;
    margin: 3% auto;
    padding: 20px;
    width: 70%;
    max-width: 600px;
    border-radius: 15px;
    border: 1px solid #2378b6;
    box-shadow: rgba(5, 2, 5, 0.5) 0px 2px 6px 0px;
    animation: zoom 0.3s;
    z-index: 10000; /* Más alto que otros elementos */
    position: relative; /* Necesario para aplicar el z-index dentro del modal */
}
.p{
    color:#2378b6;
}
  
          @keyframes zoom {
              from {transform: scale(0)} 
              to {transform: scale(1)}
          }
  
          .close {
              color: #aaa;
              float: right;
              font-size: 28px;
              font-weight: bold;
          }
  
          .close:hover,
          .close:focus {
              color: black;
              text-decoration: none;
              cursor: pointer;
          }
          #userProfileModal {
    display: none; /* Ocultar el modal por defecto */
    position: fixed; /* Para posicionarlo sobre todo */
    z-index: 9999; /* Asegúrate de que tenga el z-index más alto */
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.5); /* Fondo semitransparente */
}

      </style>
  
  
 
  
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    // Modal para el perfil de usuario
    const userProfileButton = document.getElementById('userProfileButton');
    const userProfileModal = document.getElementById('userProfileModal');
    const closeProfile = document.getElementById('closeProfile');
    
    // Mostrar modal solo cuando se hace clic en el botón (logo)
    userProfileButton.onclick = function() {
        userProfileModal.style.display = "block";
    };
    
    // Cerrar modal cuando se hace clic en el botón de cerrar
    closeProfile.onclick = function() {
        userProfileModal.style.display = "none";
    };
    
    // Cerrar modal si se hace clic fuera de él
    window.onclick = function(event) {
        if (event.target === userProfileModal) {
            userProfileModal.style.display = "none";
        }
    };
</script>
         

</body>
</html>
