function filter() {
  // Declare variables
  var input, filter, table, tr, td, i;
  inputName = document.getElementById("filterName");
  inputOrt = document.getElementById("filterOrt");
  inputWahlspruch = document.getElementById("filterWahlspruch");
  filterName = inputName.value.toUpperCase();
  filterOrt = inputOrt.value.toUpperCase();
  filterWahlspruch = inputWahlspruch.value.toUpperCase();
  table = document.getElementById("findex");
  tr = table.getElementsByClassName("eintrag");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    tdName = tr[i].getElementsByTagName("td")[0];
    tdOrt = tr[i].getElementsByTagName("td")[1];
    tdWahlspruch = tr[i].getElementsByTagName("td")[4];
    if (tdName && tdOrt && tdWahlspruch) {
      if (tdName.innerHTML.toUpperCase().indexOf(filterName) > -1 && tdOrt.innerHTML.toUpperCase().indexOf(filterOrt) > -1 && tdWahlspruch.innerHTML.toUpperCase().indexOf(filterWahlspruch) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}
