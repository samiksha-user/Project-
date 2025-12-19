let Form = document.getElementById("Form");

Form.addEventListener("submit", (e) => {
  if (!validate()) {
    e.preventDefault(); // stop submit ONLY if invalid
  }
});

// Email validation
function isEmail(email) {
  let at = email.indexOf("@");
  let dot = email.lastIndexOf(".");
  return at > 0 && dot > at + 2 && dot < email.length - 1;
}

function validate() {
  let fullname = document.getElementById("name");
  let email = document.getElementById("email");
  let phone = document.getElementById("phone");
  let password = document.getElementById("password");
  let cpassword = document.getElementById("cpassword");

  let isValid = true;

  // NAME
  if (fullname.value.trim() === "") {
    setError(fullname, "Name cannot be blank");
    isValid = false;
  } else if (fullname.value.trim().length < 3) {
    setError(fullname, "Name must be at least 3 characters");
    isValid = false;
  } else {
    setSuccess(fullname);
  }

  // EMAIL
  if (email.value.trim() === "") {
    setError(email, "Email cannot be blank");
    isValid = false;
  } else if (!isEmail(email.value.trim())) {
    setError(email, "Enter a valid email");
    isValid = false;
  } else {
    setSuccess(email);
  }

  // PHONE
  if (phone.value.trim() === "") {
    setError(phone, "Phone number cannot be blank");
    isValid = false;
  } else if (!/^\d{10}$/.test(phone.value)) {
    setError(phone, "Phone must be exactly 10 digits");
    isValid = false;
  } else {
    setSuccess(phone);
  }

  // PASSWORD
  if (password.value.trim() === "") {
    setError(password, "Password cannot be blank");
    isValid = false;
  } else if (password.value.length < 6) {
    setError(password, "Password must be at least 6 characters");
    isValid = false;
  } else {
    setSuccess(password);
  }

  // CONFIRM PASSWORD
  if (cpassword.value.trim() === "") {
    setError(cpassword, "Confirm your password");
    isValid = false;
  } else if (cpassword.value !== password.value) {
    setError(cpassword, "Passwords do not match");
    isValid = false;
  } else {
    setSuccess(cpassword);
  }

  return isValid;
}

// Show error
function setError(input, message) {
  let parent = input.parentElement;
  let small = parent.querySelector("small");

  parent.classList.add("error");
  parent.classList.remove("success");

  small.innerText = message;
  small.style.display = "block";
}

// Show success
function setSuccess(input) {
  let parent = input.parentElement;
  let small = parent.querySelector("small");

  parent.classList.add("success");
  parent.classList.remove("error");

  small.style.display = "none";
}
