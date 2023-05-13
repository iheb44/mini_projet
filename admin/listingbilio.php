<?php
include_once('../include/entete_front.inc.php');
$Biblio = new Biblio("localhost", "bibliodb", "root", "");
$users = $Biblio->getUsers();

?>
<html>
<head>
<?php include('adminNavBar.php'); ?>
  <title>Biblio - bibliothecaire</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Liste des Bibliothécaires</h2>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addLibrarianModal">Ajouter un bibliothécaire</button>
    </div>
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
        foreach ($users as $row) {
            echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['nom']}</td>
                <td>{$row['prenom']}</td>
                <td>{$row['email']}</td>
                <td><button type='button' class='btn btn-danger' data-toggle='modal' data-target='#editUserModal' data-id='{$row['id']}' data-name='{$row['nom']}' data-lastname='{$row['prenom']}' data-email='{$row['email']}'>modifier</button></td>
                </tr>";
        }

        ?>
        </tbody>
    </table>
</div>
<div class="modal fade" id="addLibrarianModal" tabindex="-1" role="dialog" aria-labelledby="addLibrarianModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="addLibrarianModalLabel">Ajouter un bibliothécaire</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addLibrarianForm">
                    <div class="form-group">
                        <label for="nom">Nom:</label>
                        <input type="text" class="form-control" id="nom" name="nom">
                    </div>
                    <div class="form-group">
                        <label for="prenom">Prénom:</label>
                        <input type="text" class="form-control" id="prenom" name="prenom">
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
                    <div class="form-group">
                        <label for="password">Mot de passe:</label>
                        <input type="password" class="form-control" id="password" name="password" autocomplete="false">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" onclick="submitLibrarian()">Ajouter</button>
            </div>
        </div>
    </div>
</div>


<div id="editUserModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit User</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form id="editUserForm" method="POST" action="#">
            <div class="form-group">
              <label for="editName">Name:</label>
              <input type="text" class="form-control" id="editName" name="editName" required>
            </div>
            <div class="form-group">
              <label for="editLastName">Last Name:</label>
              <input type="text" class="form-control" id="editLastName" name="editLastName" required>
            </div>
            <div class="form-group">
              <label for="editEmail">Email:</label>
              <input type="email" class="form-control" id="editEmail" name="editEmail" required>
            </div>
            <input type="hidden" id="editUserId" name="editUserId" value="">
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="updateUser">Update</button>
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

    $(document).ready(function() {
      $('#editUserModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var userId = button.data('id');
        var name = button.data('name');
        var lastName = button.data('lastname');
        var email = button.data('email');
        var modal = $(this);
        modal.find('#editUserId').val(userId);
        modal.find('#editName').val(name);
        modal.find('#editLastName').val(lastName);
        modal.find('#editEmail').val(email);

        $('#updateUser').click(function() {
          var new_name = $('#editName').val();
          var new_lastName = $('#editLastName').val();
          var new_email = $('#editEmail').val();
          $.ajax({
            type: "POST",
            url: "update_librarian.php",
            data: {
              user_id: userId,
              name: new_name,
              lastName: new_lastName,
              email: new_email
            },
            success: function() {
            }
          });
        });
      });
    });


    function submitLibrarian() {
      const form = document.getElementById('addLibrarianForm');
      const nom = form.nom.value;
      const prenom = form.prenom.value;
      const email = form.email.value;
      const password = form.password.value;

      $.ajax({
        type: 'POST',
        url: 'add_librarian.php',
        data: {
          nom,
          prenom,
          email,
          password
        },
        success: function() {
        },
        error: function(xhr, status, error) {
          console.error(error);
        }
      });
    }
  </script>
</body>

</html>