// Returns a function, that, as long as it continues to be invoked, will not
// be triggered. The function will be called after it stops being called for
// N milliseconds. If `immediate` is passed, trigger the function on the
// leading edge, instead of the trailing.
function debounce(func, wait, immediate) {
	var timeout;
	return function() {
		var context = this, args = arguments;
		var later = function() {
			timeout = null;
			if (!immediate) func.apply(context, args);
		};
		var callNow = immediate && !timeout;
		clearTimeout(timeout);
		timeout = setTimeout(later, wait);
		if (callNow) func.apply(context, args);
	};
};

function filter() {
  debounce(innerFilter(),500);
}

function innerFilter() {
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
