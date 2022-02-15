<!--Mock api framework-less -->


<?php include 'globals/header.php' ?>
<div class="container">
<button style="display: none;" id="hidden"></button>
<div id="overflow">
 
  <form id="newUser" method="POST" action="">
  <div>  
    <a href="" id="close">Close</a>
  </div>
  <ul>
   <li>
    <label form ="nume">Nume si prenume</label>
    <input type="text" name="nume" id="nume" required>
  </li>
  <li>
    <label form ="age">Varsta</label>
    <input type="text" name="age" id="age" required>
  </li> 
   <li> 
    <label form ="telefon">Telefon</label>
    <input type="text" name="telefon" id = "telefon" required>
   </li>
    <li>    
    <label form ="email">Email</label>
    <input type="email" name="email" id = "email" required>
   </li>   
    <li>
    <input id="go" type="submit">
   </li> 
   </ul>
  </form>
</div>
  <span id="appendId"></span>
<div id="overflow_edit">
 
  <form id="editUser" method="POST" action="">
  <div>  
    <a href="" id="close_edit">Close</a>
  </div>
  <ul>
   <li>
    <label form ="nume_edit">Nume si prenume</label>
    <input type="text" name="nume_edit" id="nume_edit" required>
  </li>
  <li>
    <label form ="age_edit">Varsta</label>
    <input type="text" name="age_edit" id="age_edit" required>
  </li> 
   <li> 
    <label form ="telefon_edit">Telefon</label>
    <input type="text" name="telefon_edit" id = "telefon_edit" required>
   </li>
    <li>    
    <label form ="email_edit">Email</label>
    <input type="email" name="email_edit" id = "email_edit" required>
   </li>   
    <li>
    <input id="update" value="Update" type="submit">
   </li> 
   </ul>
  </form>
</div>



<div class="main">
  <div class="depart">
  <a href = "" id="adauga">Adauga</a>
</div>

<table>
<thead>
  <th>Nume</th>
  <th>Varsta</th>
  <th>Telefon</th>
  <th>Email</th>
  <th></th>
</thead>
<tbody id= "table_body">
</tbody>
</table>
</div>
</div>






<?php include 'globals/footer.php' ?>

<!--Tot read-ul este dynamic-->



<!--Facut doar pentru incercare curl, normal nu fac request catre server pt afisare , readUser vine direct in lading page prin metode normale-->

<?php
function readUsers() 
{
  $host = $_SERVER['HTTP_HOST'] ;

  $url = "http://$host/api/read.php";

  $curl = curl_init($url);
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

  $headers = array(
    "Accept: application/json",
  );
  curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

  $resp = curl_exec($curl);
  curl_close($curl);

  return $resp;

}

?>

<script>

  /*

  !!!!!  Sa nu se faca operatiuni prea repede pe new si update, baza de date nu are cum sa faca operatiunile acestea mai repede decat select

  */

   const tableBody = $('#table_body');

   const btn = $('#adauga');

   const overflow = $('#overflow');

   const overflowEdit = $('#overflow_edit');

   const close = $('#close');

   const closeEdit = $('#close_edit');

   const deleted = document.getElementsByClassName('deleted');

   const edited = document.getElementsByClassName('edited');

   const nume_edit = document.getElementById('nume_edit');

   const age_edit = document.getElementById('age_edit');

   const telefon_edit = document.getElementById('telefon_edit');
   
   const email_edit = document.getElementById('email_edit');

   const update = $('#update');

   let editId = 0;
   
   function createBody(body) {
    let template = 
          `<tr>
                <td>${body.nume}</td>
                <td>${body.age}</td>
                <td>${body.telefon}</td>
                <td>${body.email}</td>
                <td><a class="edited" style=" 
                background-color: green;
                color: white;
                border-radius: 6px;
                padding: 8px 16px;
                text-decoration: none;" 
                value= ${body.id} href="#">Edit</a>
                <a class="deleted" style="
                margin-left: 10px; 
                background-color: red;
                color: white;
                border-radius: 6px;
                padding: 8px 16px;
                text-decoration: none;" 
                value= ${body.id} href="#">Delete</a>
                </td>
           </tr>`

      return template;     
  }

  function ajaxReadSingle(newId, clicked=false) {
    let idToSend = {
      idsend : newId
    } 

      $.ajax({
        url: window.location.href + '/api/read_single.php',
        method: 'GET',
        data: idToSend,
        success: function (data) {
          if(clicked) {
            tableBody.append(createBody(data))

            edited[edited.length-1].addEventListener("click", (e)=>{
              e.preventDefault();
              
              let curentEditId = e.target.getAttribute('value');

              editId = curentEditId;

              ajaxReadSingle(curentEditId, false);

              overflowEdit.css("display","block");

              e.target.setAttribute("listen","listen");
            });
            deleted[deleted.length-1].addEventListener("click",(e)=>{
              e.preventDefault();

              let curentId = e.target.getAttribute('value');  

              ajaxDelete(curentId);

              ((e.target.parentElement).parentElement).remove(); 

            }); 
          } else {
            
            nume_edit.value = data.nume;
            
            age_edit.value = data.age;
            
            telefon_edit.value = data.telefon;
            
            email_edit.value = data.email;   
          } 
       }
       
      });
  }



  function ajaxCreate (data) {
      $.ajax({
            url: window.location.href +'/api/create.php',
            method: 'POST',
            data : data,
            succes: function(data) {
                console.log(success)
            }
      });
  }


  function ajaxDelete (id) {
   
    $.ajax({
          url: window.location.href +'/api/delete.php',
          method: 'DELETE',
          data : id,
          succes: function(data) {
            console.log(success)
          }
    });
  }

  function ajaxEdit(dataSent) {
    $.ajax({
          url: window.location.href + '/api/edit.php',
          method: 'PUT',
          data : dataSent,
          succes: function(data) {
              console.log(success)  
          }
    });
  }

  

  function appendBody () 
  {
    let body = <?php echo readUsers() ?>;
    body.forEach( (element ) => {
        tableBody.append(createBody(element))
       
  })
    if(deleted || deleted.length !== 0)
    {
          for(let i = 0 ; i < deleted.length; i++) 
          {  
            let curentId = deleted[i].getAttribute('value');

            deleted[i].addEventListener("click", (e) =>{
              e.preventDefault();

              ajaxDelete(curentId);
            ((e.target.parentElement).parentElement).remove();  

            });

            deleted[i].setAttribute('listen', 'true');

            edited[i].addEventListener("click", (e) => {
              e.preventDefault();
              
              let curentEditId = e.target.getAttribute('value');

              editId = curentEditId;

              ajaxReadSingle(editId, false);

              overflowEdit.css("display","block");
            });
            edited[i].setAttribute('listen', 'true');
         }
    }
  }

  $('#update').click((e)=>{
      e.preventDefault();
      let sendData= {
        id: editId,
        nume: $('#nume_edit').val(),
        age : $('#age_edit').val(),
        telefon: $('#telefon_edit').val(),
        email: $('#email_edit').val()
      }
      
      ajaxEdit(sendData);

      for(let i = 0; i< edited.length; i++) {
        
        if(parseInt(edited[i].getAttribute('value')) == editId)
        {
          ((edited[i].parentElement).parentElement).remove();  
        }
  
      };

      console.log(editId);
      let newId = editId  
      setTimeout(() => {
        ajaxReadSingle(newId, true);
      },1000); 

      closeEdit.click();

  });





  $('#go').click((e)=>{
    e.preventDefault();
      let nume =   document.getElementById('nume').value 
      let varsta =   document.getElementById('age').value 
      let telefon =   document.getElementById('telefon').value 
      let email =   document.getElementById('email').value 

    let data = {
      Nume : nume,
      Varsta : varsta,
      Telefon : telefon,
      Email : email
    };

    if(!nume || !varsta || !telefon || !email) 
    {
      alert('Completati toate campurile');

    } else {

      ajaxCreate(data);

      let id = ''
 setTimeout(() => {
        ajaxReadSingle(id, true);
     },500);
      document.getElementById('nume').value = '';
      document.getElementById('age').value  = '';
      document.getElementById('telefon').value  = '';
      document.getElementById('email').value  = '';
      
      overflow.css("display","none")

    }
  }
  );


    

  btn.click((e)=>{
    e.preventDefault();
      overflow.css("display","block")
  }) 

  close.click((e)=>{
    e.preventDefault();
        overflow.css("display","none")
  });

  closeEdit.click((e)=>{
    e.preventDefault();
    overflowEdit.css("display","none")
    editId = 0;    
  });

  $(document).ready(appendBody);

</script>





