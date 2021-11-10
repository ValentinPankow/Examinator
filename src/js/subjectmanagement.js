//Benachrichtungs Element erzeugen
//(DH) (C&P von VP)
const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  customClass: {
      popup: "swal2-popup-custom"
  },
  didOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
})

//Öffnet die Funktion zum abspeichern eines neuen Fach beim anklicken des Buttons "Anlegen"
//(DH)
$('#saveSubject').on('click', function() {
  saveNewSubject();
});

//Speichert ein neues Fach über "Anlegen"
//(DH)(Zumeist C&P von VP mit Anpassungen)
function saveNewSubject()
{
  let name = $('#createName').val();

  // Variable für Fehlermeldung
  let errorMsg = null;

  //Fehlermeldung falls kein Name eingegeben wurde
  if ($('#createName').val() == '') {
      errorMsg = $('.noNameSelected').html();
  }

  //Falls eine Fehlermeldung entsteht, diese zurückgeben [...]
  if (errorMsg != null) {
      triggerResponseMsg('error', errorMsg);
      return false;
  //[...] ansonsten das neue Fach versuchen anzulegen
  } else {
      $.post(
          'src/php/_ajax/ajax.querySubject.php',
          {
              data: {
                  action: 'insert',
                  name: name,
              },
          },
          function(rtn) {
              try {
                  let obj = JSON.parse(rtn);
                  if (obj.success) {
                      // Eingabemaske zurücksetzen
                      $('#createName').val('');
                      triggerResponseMsg('success', $('.successCreateSubject').html());
                      setTimeout(function() {window.location.reload();}, 3000);
                  } else {
                      triggerResponseMsg('error', $('.errorCreateSubject').html());
                  }
              } catch(e) {
                  console.log(e);
              }
          }
      );
  }
}

//Öffnet das Modal zum editieren in der Fachverwaltung
//(DH)(Zumeist C&P von VP mit Anpassungen)
$('.edit').on('click',  function () {
  let button = $(this);
  $('#editSubjectModal').find('button[name="editSubject"]').attr('data-id', button.attr('data-id'));
  $('#editSubjectModal').modal('show');
});


//Wenn das Modal geöffnet wurde, das Fach laden
//(DH)(Zumeist C&P von VP mit Anpassungen)
$('#editSubjectModal').on('shown.bs.modal', function() {
  getSubject($('#editSubjectModal').find('button[name="editSubject"]').attr('data-id'));
});



//Öffnet die Funktion zum editieren einer Klasse beim anklicken des Buttons "Ändern"
//(DH)(Zumeist C&P von VP mit Anpassungen)
$('#editSubjectModal').find('button[name="editSubject"]').on('click', function() {
  editSubject($('#editSubjectModal').find('button[name="editSubject"]').attr('data-id'));
});



//Ändert das Fach. Speichert bei Erfolg in die Datenbank, ansonsten gibt es eine Error-Meldung.
//(DH)(Zumeist C&P von VP mit Anpassungen)
function editSubject(id)
{
  let name = $('#editName').val();

  // Variable für Fehlermeldung
  let errorMsg = null;

  if ($('#editName').val() == '') {
      errorMsg = $('.noNameSelected').html();
  }

  if (errorMsg != null) {
      triggerResponseMsg('error', errorMsg);
      return false;
  } else {
      $.post(
          'src/php/_ajax/ajax.querySubject.php',
          {
              data: {
                  id: id,
                  action: 'update',
                  name: name,
              },
          },
          function (rtn) {
              try {
                  let obj = JSON.parse(rtn);
                  if (obj.success) {
                      triggerResponseMsg('success', $('.successEditSubject').html());
                      setTimeout(function() {window.location.reload();}, 3000);
                  } else {
                      triggerResponseMsg('error', $('.errorEditSubject').html());
                  }
                  $('#editSubjectModal').modal('hide');
              } catch (e) {
                  console.log(e);
                  triggerResponseMsg('error', $('.errorEditSubject').html());
                  $('#editSubjectModal').modal('hide');
              }
          }
      );
  }
}



function getSubject(id) {
  $.post(
      'src/php/_ajax/ajax.getSubject.php',
      {
          data: {
              id: id
          },
      },
      function (rtn) {
          try {
              let obj = JSON.parse(rtn);

              if (obj.success) {

                  // Zurückbekommene Werte den Feldern zuteilen
                  $('#editName').val(obj.subject.name);
                  $('#editSubjectModal').find('.overlay').fadeOut(500);
              } else {
                  triggerResponseMsg('error', $('.errorGetSubject').html());
                  $('#editSubjectModal').modal('hide');
              }
          } catch (e) {
              console.log(e);
              triggerResponseMsg('error', $('.errorGetSubject').html());
              $('#editSubjectModal').modal('hide');
          }
      }
  );
}


// DH (Zumeist C&P von VP mit Anpassungen)
//Öffnet das Modal zum editieren in der Klassenverwaltung
$('.delete').on('click',  function () {
  let button = $(this);
  $('#deleteSubjectModal').find('button[name="deleteSubject"]').attr('data-id', button.attr('data-id'));

  $('#deleteSubjectModal').modal('show');
});


$('#deleteSubjectModal').find('button[name="deleteSubject"]').on('click', function() {
  deleteSubject($('#deleteSubjectModal').find('button[name="deleteSubject"]').attr('data-id'));
});

function deleteSubject(id) {
  $.post(
      'src/php/_ajax/ajax.deleteSubject.php',
      {
          data: {
              id: id
          },
      },
      function(rtn) {
          try {
              let obj = JSON.parse(rtn);
              if (obj.success) {
                  triggerResponseMsg('success', $('.successDeleteSubject').html());
                  setTimeout(function() {window.location.reload();}, 3000);
              } else {
                  triggerResponseMsg('error', $('.errorDeleteSubject').html());
              }
              $('#deleteSubjectModal').modal('hide');
          } catch(e) {
              console.log(e);
          }
      }
  );
}
