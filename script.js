function filter() {
  // Declare variables 
  var input, filter, table, tr, td, i;
  inputName = document.getElementById("filterName");
  inputOrt = document.getElementById("filterOrt");
  filterName = inputName.value.toUpperCase();
  filterOrt = inputOrt.value.toUpperCase();
  table = document.getElementById("findex");
  tr = table.getElementsByClassName("eintrag");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    tdName = tr[i].getElementsByTagName("td")[0];
    tdOrt = tr[i].getElementsByTagName("td")[1];
    if (tdName && tdOrt) {
      if (tdName.innerHTML.toUpperCase().indexOf(filterName) > -1 && tdOrt.innerHTML.toUpperCase().indexOf(filterOrt) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    } 
  }
}
