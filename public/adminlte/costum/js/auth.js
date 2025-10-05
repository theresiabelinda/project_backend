const API_URL = "http://127.0.0.1:8000/api";

// LOGIN
$("#loginForm").on("submit", function(e) {
  e.preventDefault();
  $.post(`${API_URL}/login`, {
    username: $("#username").val(),
    password: $("#password").val()
  }).done(res => {
    localStorage.setItem("token", res.data.token);
    window.location.href = "dashboard.html";
  }).fail(err => {
    alert("Login gagal");
  });
});

// REGISTER
$("#registerForm").on("submit", function(e) {
  e.preventDefault();
  $.post(`${API_URL}/register`, {
    name: $("#name").val(),
    username: $("#username").val(),
    email: $("#email").val(),
    password: $("#password").val(),
    password_confirmation: $("#password_confirmation").val()
  }).done(res => {
    alert("Registrasi sukses, silakan login");
    window.location.href = "login.html";
  }).fail(err => {
    alert("Registrasi gagal");
  });
});

// DASHBOARD → GET USER LIST
if (window.location.pathname.includes("dashboard.html")) {
  const token = localStorage.getItem("token");
  if (!token) {
    window.location.href = "login.html";
  }
  $.ajax({
    url: `${API_URL}/users`,
    headers: { "Authorization": "Bearer " + token },
    success: function(res) {
      res.forEach(user => {
        $("#userList").append(`<li>${user.name} (${user.email})</li>`);
      });
    },
    error: function() {
      alert("Gagal ambil data user, mungkin token invalid.");
      window.location.href = "login.html";
    }
  });
}

// LOGOUT
$("#logoutBtn").on("click", function() {
  localStorage.removeItem("token");
  window.location.href = "login.html";
});
