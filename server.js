document
  .getElementById("loginForm")
  .addEventListener("submit", async function (event) {
    event.preventDefault(); // منع إعادة تحميل الصفحة

    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;

    try {
      const response = await fetch("test.json");
      const users = await response.json();

      const user = users.find(
        (user) => user.username === username && user.password === password
      );

      if (user) {
        alert("Login successful!");
      } else {
        alert("Invalid username or password.");
      }
    } catch (error) {
      console.error("Error fetching users:", error);
    }
  });
