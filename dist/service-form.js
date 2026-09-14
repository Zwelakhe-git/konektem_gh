"use strict";
(() => {
  // public/tmp-build/js/service-form.js
  function showMessage(message, type = "error") {
    const messageDiv = document.getElementById("formMessage");
    if (!messageDiv) return;
    messageDiv.className = type === "success" ? "bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" : "bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative";
    messageDiv.innerHTML = `
        <span class="font-medium">${type === "success" ? "Successful!" : "Fail!"}</span>
        <span class="block sm:inline ml-2">${message} ${type === "success" ? ", our manager will contact you shortly. Please wait to be redirected to the payment page" : ""}
        </span>
        <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="this.parentElement.classList.add('hidden')">
            <i class="fas fa-times"></i>
        </span>
    `;
    messageDiv.classList.remove("hidden");
    setTimeout(() => {
      messageDiv.classList.add("hidden");
    }, 8e3);
  }
  function formatPhoneNumber(input) {
    let value = input.value.replace(/\D/g, "");
    if (value.length > 0) {
      value = "+7" + value.substring(1);
    }
    if (value.length > 2) {
      value = value.substring(0, 2) + " (" + value.substring(2);
    }
    if (value.length > 7) {
      value = value.substring(0, 7) + ") " + value.substring(7);
    }
    if (value.length > 12) {
      value = value.substring(0, 12) + "-" + value.substring(12);
    }
    if (value.length > 15) {
      value = value.substring(0, 15) + "-" + value.substring(15, 17);
    }
    input.value = value;
  }
  function animateElements() {
    const elements = document.querySelectorAll(".form-section-custom, .sidebar-enhanced");
    elements.forEach((element, index) => {
      element.style.opacity = "0";
      element.style.transform = "translateY(20px)";
      setTimeout(() => {
        element.style.transition = "all 0.6s ease";
        element.style.opacity = "1";
        element.style.transform = "translateY(0)";
      }, index * 150);
    });
  }
  function setupFormHandlers(serviceData) {
    const form = document.getElementById("serviceOrderForm");
    if (!form) return;
    form.addEventListener("submit", async function(e) {
      e.preventDefault();
      const submitBtn = this.querySelector('button[type="submit"]');
      const originalText = submitBtn.innerHTML;
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>\u041E\u0442\u043F\u0440\u0430\u0432\u043A\u0430...';
      submitBtn.disabled = true;
      const formData = new FormData(this);
      let str = formData.get("full_name");
      const i = str.indexOf(" ");
      let first_name = "", last_name = "";
      if (i === -1) first_name = str;
      else [first_name, last_name] = [str.slice(0, i), str.slice(i + 1)];
      const orderData = {
        item_name: "service",
        item_id: formData.get("service_id"),
        first_name,
        last_name,
        email: formData.get("email"),
        phone: formData.get("phone"),
        message: formData.get("message"),
        total_amount: serviceData.price,
        privacy_policy: formData.get("privacy_policy") === "on",
        contact_agree: formData.get("contact_agree") === "on"
      };
      try {
        const response = await fetch("/php/dbReader.php?q=createOrder", {
          method: "POST",
          headers: {
            "Content-Type": "application/json"
          },
          body: JSON.stringify(orderData)
        });
        const result = await response.json();
        if (result.success) {
          showMessage(result.message, "success");
          setTimeout(() => {
            form.reset();
            window.location.href = "/?p=payments&f=service&id=" + serviceData.id + "&orderid=" + result.order_id;
          }, 5e3);
        } else {
          showMessage(result.message || "There was an server error when sending the form. please try again later");
        }
      } catch (error) {
        console.error("Error:", error);
        showMessage("An error occured. please try again");
      } finally {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
      }
    });
    const phoneInput = document.getElementById("phone");
    if (phoneInput) {
      phoneInput.addEventListener("input", function() {
        formatPhoneNumber(this);
      });
      phoneInput.addEventListener("blur", function() {
        if (this.value && this.value.replace(/\D/g, "").length < 11) {
          showMessage("Please enter your phone number");
        }
      });
    }
  }
  if (!document.getElementById("serviceOrderForm")) {
    let observer = new MutationObserver(() => {
      try {
        if (document.getElementById("serviceOrderForm")) {
          animateElements();
          setupFormHandlers();
          observer.disconnect();
        }
      } catch (err) {
        console.error(err.message);
      }
    });
    observer.observe(document.body, { childList: true });
  } else {
    animateElements();
    setupFormHandlers();
  }
})();
