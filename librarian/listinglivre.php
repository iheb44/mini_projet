<?php
include_once('../include/entete_front.inc.php');

$BookList = new BookList("localhost", "bibliodb", "root", "");
$books = $BookList->getBooks();

if (isset($_POST['emprunter'])) {
    $livre_id = $_POST['livre_id'];
    $user_id = $_SESSION['user_id'];
    $BookList->emprunterBiblio($user_id, $livre_id);
}

if (isset($_POST['addBook'])) {
    $titre = $_POST['titre'];
    $auteur = $_POST['auteur'];
    $genre = $_POST['genre'];
    $copies_disponibles = $_POST['copies_disponibles'];

    $target_file = null;

    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $target_dir = "../assets/images/couvertures/";
        $target_file = basename($_FILES["image"]["name"]);
        $target_file_path = $target_dir.basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $extensions_arr = array("jpg", "jpeg", "png", "gif");

        if (in_array($imageFileType, $extensions_arr)) {
            move_uploaded_file($_FILES['image']['tmp_name'], $target_file_path);
        }
    }

    $BookList->addBook($titre, $auteur, $genre, $copies_disponibles, $target_file);

    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
}

if (isset($_POST['returnCheckbox'])) {
    $BookList->returnBooks($_POST['returnCheckbox']);
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
}
?>
<html>
<head>
  <title>Biblio - Livres</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Titre de votre page</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet"  type='text/css'>
    <link rel="stylesheet" href="../assets/css/style/navbar.css">
    <link rel="stylesheet" href="../assets/css/style/listing.css">
</head>
<body>
<?php include('libNavBar.php'); ?>
  <div class="container">
    <h2>Livres</h2>
      <div class="form-group row">
          <div class="col-sm-10">
              <input type="text" id="filter" class="form-control" placeholder="Rechercher par titre, auteur ou genre">
          </div>
          <div class="col-sm-2">
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addBookModal">Ajouter un
                  livre
              </button>
          </div>
      </div>
      <div class="table-responsive">
          <table id="myTable" class="table table-striped w-100" width="100">
              <thead>
              <tr>
                  <th>Titre</th>
                  <th class="text-center">Auteur</th>
                  <th class="text-center">Genre</th>
                  <th class="text-center">Disponibilité</th>
                  <th class="text-center">Image</th>
                  <th class="text-center">Action</th>
              </tr>
              </thead>
              <tbody>
              <?php foreach ($books as $book) {
                  $button = '';
                  if ($book['copies_disponibles'] > 0) {
                      $button = "<button type='button' class='btn btn-danger emprunter' data-toggle='modal' data-target='#myModal' data-id='{$book['id']}' data-titre='{$book['titre']}' data-auteur='{$book['auteur']}' data-genre='{$book['genre']}' data-copies='{$book['copies_disponibles']}'><i class='fa fa-edit'></i></button>";
                  }
                  ?>
                  <tr>
                      <td><?php echo $book['titre']; ?></td>
                      <td class="text-center"><?php echo $book['auteur']; ?></td>
                      <td class="text-center"><?php echo $book['genre']; ?></td>
                      <?php
                      if ($book['copies_disponibles'] == 0) {
                          echo "<td class='bg-danger text-white text-center' width='5'> Non disponible </td>";
                      } else {
                          echo "<td class='bg-success text-white text-center' width='5'> Disponible </td>";
                      }
                      ?>
                      <td class="text-center" style="width: 10%">
                          <img class="book-image" src="../assets/images/couvertures/<?php echo $book['image_url']; ?>"
                               onerror="this.onerror=null; this.src='../assets/images/image_non_disponible.png';"
                               alt="Image de <?php echo $book['titre']; ?>">
                      </td>
                      <td class="text-center"><?php echo $button ?></td>
                  </tr>
              <?php } ?>
              </tbody>
          </table>
      </div>
      <div id="myModal" class="modal fade" role="dialog" data-backdrop="static">
          <div class="modal-dialog modal-lg">
              <div class="modal-content">
                  <div class="modal-header bg-info text-white">
                      <h4 class="modal-title">Etudiants qui ont emprunté le livre</h4>
                      <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  <div class="modal-body">
                      <input type="text" id="filtermodal" class="form-control mb-2 w-50" placeholder="Rechercher email">
                      <form method="post" action="">
                          <table class="table table-striped">
                              <thead>
                              <tr>
                                  <th>Email</th>
                                  <th>Emprunté le</th>
                                  <th>Retour Prévu le</th>
                                  <th>Livre retourné</th>
                              </tr>
                              </thead>
                              <tbody>

                              </tbody>
                          </table>
                          <div class="text-right">
                              <button type="submit" class="btn btn-primary" name="validateReturns">Valider les retours</button>
                          </div>
                      </form>
                  </div>
              </div>
          </div>
      </div>

      <div id="addBookModal" class="modal fade" role="dialog">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header bg-info text-white">
                      <h4 class="modal-title">Ajouter un livre</h4>
                      <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  <div class="modal-body">
                      <form method="post" action="" enctype="multipart/form-data">
                          <div class="form-group">
                              <label for="titre">Titre:</label>
                              <input type="text" class="form-control" id="titre" name="titre">
                          </div>
                          <div class="form-group">
                              <label for="auteur">Auteur:</label>
                              <input type="text" class="form-control" id="auteur" name="auteur">
                          </div>
                          <div class="form-group">
                              <label for="genre">Genre:</label>
                              <input type="text" class="form-control" id="genre" name="genre">
                          </div>
                          <div class="form-group">
                              <label for="copies_disponibles">Copies disponibles:</label>
                              <input type="number" class="form-control" id="copies_disponibles" name="copies_disponibles">
                          </div>
                          <div class="form-group">
                              <label for="image">Image:</label>
                              <input type="file" class="form-control" name="image" id="image" required>
                          </div>
                          <button type="submit" class="btn btn-info" name="addBook">Ajouter</button>
                      </form>
                  </div>
              </div>
          </div>
      </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    document.getElementById('filter').addEventListener('keyup', debound(filter_table, 500));

    function filter_table(e) {
      const rows = document.querySelectorAll('tbody tr')
      rows.forEach(row => {
        const titre = row.querySelector('td:nth-child(2)').innerText.toLowerCase();
        const autre = row.querySelector('td:nth-child(3)').innerText.toLowerCase();
        const Genre = row.querySelector('td:nth-child(4)').innerText.toLowerCase();
        if (titre.includes(e.target.value.toLowerCase()) || autre.includes(e.target.value.toLowerCase()) || Genre.includes(e.target.value.toLowerCase())) {
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
        $('.emprunter').on('click', function() {
            var bookId = $(this).data('id');

            $.get('get_students.php', { book_id: bookId }, function(data) {
                var students = JSON.parse(data);

                $('#myModal .modal-body tbody').empty();

                students.forEach(function(student, index) {
                    var checkbox = $('<input>').attr({
                        type: 'checkbox',
                        name: 'returnCheckbox[' + index + ']',
                        value: student.id,
                        class: 'form-check-input'
                    });
                    var tr = $('<tr>').append(
                        $('<td class="text-center">').text(student.email),
                        $('<td class="text-center">').text(student.emprunt_date),
                        $('<td class="text-center">').text(student.date_retour),
                        $('<td class="text-center">').append(checkbox)
                    );
                    $('#myModal .modal-body tbody').append(tr);
                });
            });
        });
    });

    document.getElementById('filtermodal').addEventListener('keyup', debound(filter_modal, 500));

    function filter_modal(e) {
        const rows = document.querySelectorAll('#myModal .modal-body tbody')
        rows.forEach(row => {
            const email = row.querySelector('td:nth-child(1)').innerText.toLowerCase();
            if (email.includes(e.target.value.toLowerCase())) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        })
    }

  </script>
</body>

</html>