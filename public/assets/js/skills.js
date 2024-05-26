document.querySelector('#add').onclick = function () {
  'use strict';
  if (document.querySelector('#input-add').value === '') {
      document.querySelector('.alert').style.display = "block";
      document.querySelector('.alert h2').textContent = "full the field please";
      document.querySelector('.alert button').onclick = function () {
          document.querySelector('.alert').style.display = "none";
      };
  } else {
      var span = document.createElement('span'),
          times = document.createElement('i');
      times.setAttribute('class', 'fas fa-times');
      span.textContent = document.querySelector('#input-add').value;
      span.appendChild(times);
      document.querySelector('.items').appendChild(span);
      document.querySelector('#input-add').value = "";
      times.onclick = function () {
          this.parentElement.style.display = "none";
      };
  }
};