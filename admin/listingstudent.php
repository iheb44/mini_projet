<?php

include_once('../include/entete_front.inc.php');

$studentManager = new StudentManager("localhost", "bibliodb", "root", "");
$students = $studentManager->getStudents();
session_start();
?>

<html>

<head>
<?php include('adminNavBar.php'); ?>
  <title>Biblio - etudiant</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../assets/css/style/listing.css">
</head>

<body>
  <div class="container">
    <h2>Liste des etudiants</h2>
    <div class="form-group">
      <input type="text" id="filter" class="form-control" placeholder="Rechercher par Nom, Prenom ou Email">
    </div>
    <table id="myTable" class="table table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nom</th>
          <th>Prenom</th>
          <th>Email</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php

        foreach ($students as $row) {
          echo "<tr>
                      <td>{$row['id']}</td>
                      <td>{$row['nom']}</td>
                      <td>{$row['prenom']}</td>
                      <td>{$row['email']}</td>
                      <td><button type='button' class='btn btn-danger' onclick='confirmDelete({$row['id']}, \"{$row['nom']} {$row['prenom']}\")'>Supprimer</button></td>
                  </tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
  <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmModalLabel">Confirmation de suppression</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Voulez-vous vraiment supprimer l'étudiant <strong><span data-name></span></strong> ?</p>
          <input type="text" name="id">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
          <button type="button" class="btn btn-danger" onclick="deleteStudent()">Supprimer</button>
        </div>
      </div>
    </div>
  </div>



  <script>
    document.getElementById('filter').addEventListener('keyup', debound(filter_table, 500))

    function filter_table(e) {
      const rows = document.querySelectorAll('tbody tr')
      rows.forEach(row => {
        const nom = row.querySelector('td:nth-child(2)').innerText.toLowerCase()
        const prenom = row.querySelector('td:nth-child(3)').innerText.toLowerCase()
        const email = row.querySelector('td:nth-child(4)').innerText.toLowerCase()
        if (nom.includes(e.target.value.toLowerCase()) ||
          prenom.includes(e.target.value.toLowerCase()) ||
          email.includes(e.target.value.toLowerCase())) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      })
    }

    function debound(func, timeout) {
      let timer
      return (...args) => {
        if (!timer) {
          func.apply(this, args);
        }
        clearTimeout(timer)
        timer = setTimeout(() => {
          func.apply(this, args)
          timer = undefined
        }, timeout)
      }
    }

    function confirmDelete(id, name) {
      const modal = $('#confirmModal');
      modal.find('span[data-name]').text(name);
      modal.find('input[name=id]').val(id);
      modal.modal('show');
    }

    function deleteStudent() {
      const modal = $('#confirmModal');
      const id = modal.find('input[name=id]').val();
      $.ajax({
        type: 'POST',
        url: 'delete_student.php',
        data: {
          id: id
        },
        success: function() {
          window.location.reload();
        },
        error: function() {
          alert('Une erreur s\'est produite lors de la suppression de l\'étudiant.');
        }
      });
    }
  </script>
</body>

</html>