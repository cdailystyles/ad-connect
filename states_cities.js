const statesCities = {
  "California": ["Los Angeles", "San Francisco", "San Diego"],
  "Texas": ["Houston", "Dallas", "Austin"],
  // Add more states and cities as needed
};

document.addEventListener("DOMContentLoaded", () => {
  const stateSelect = document.getElementById("state");
  const citiesSelect = document.getElementById("cities");

  stateSelect.addEventListener("change", () => {
    const selectedState = stateSelect.value;
    const cities = statesCities[selectedState] || [];
    citiesSelect.innerHTML = "";

    cities.forEach(city => {
      const option = document.createElement("option");
      option.value = city;
      option.textContent = city;
      citiesSelect.appendChild(option);
    });
  });
});

