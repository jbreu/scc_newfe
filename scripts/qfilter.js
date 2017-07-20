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
    input = document.getElementById("filter");
    filter = input.value.toUpperCase();
    table = document.getElementById("qtable");
    tr = table.getElementsByTagName("tr");

    // Loop through all table rows, and hide those who don't match the search query
    for (i = 0; i < tr.length; i++) {
      td0 = tr[i].getElementsByTagName("td")[0];
			td1 = tr[i].getElementsByTagName("td")[1];
			td2 = tr[i].getElementsByTagName("td")[2];
			td3 = tr[i].getElementsByTagName("td")[3];
			td4 = tr[i].getElementsByTagName("td")[4];
      if (td0 && td1 && td2 && td3 && td4) {
        if (td0.innerHTML.toUpperCase().indexOf(filter) > -1 ||
						td1.innerHTML.toUpperCase().indexOf(filter) > -1 ||
						td2.innerHTML.toUpperCase().indexOf(filter) > -1 ||
						td3.innerHTML.toUpperCase().indexOf(filter) > -1 ||
						td4.innerHTML.toUpperCase().indexOf(filter) > -1
						) {
          tr[i].style.display = "";
        } else {
          tr[i].style.display = "none";
        }
      }
    }
  }
