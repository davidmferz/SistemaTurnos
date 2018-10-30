function consulta_Select(tabla, select)
    {
      $.ajax({
          url:"consulta_Select.php?tabla="+tabla,
          type:"GET",
          dataType:"json",
          cache:false,
          contentType: false,
          encode:true,
          processData: false,
          beforeSend: function()
          {
               
          },
          success: function(datos)
          {
              var t_re=document.getElementById(select);
              t_re.innerHTML="<option value='0'>Seleccione una opción</option>";
              for (i in datos) 
              {
                  t_re.innerHTML +=`<option value='${datos[i].name}'>${datos[i].name}</option>`;
              }
          },
          error: function(XMLHttpRequest)
          {
             console.log("error"+XMLHttpRequest); 
          }
      });
    }