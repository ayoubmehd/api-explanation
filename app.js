const tbody = document.querySelector("#tbody");
const addForm = document.querySelector("#addForm");

fetch("http://consume-api-test-2.test/api.php")
    .then(res => res.json())
    .then(res => {
        res.data.forEach(element => {
            tbody.innerHTML += `
                <tr>
                <td>${element.id}</td>
                <td>${element.firstname}</td>
                <td>${element.lastname}</td>
                </tr>
            `;
        });
    });


addForm.addEventListener("submit", (event) => {
    event.preventDefault();
    fetch("http://consume-api-test-2.test/api.php", {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            firstname: addForm.firstname.value,
            lastname: addForm.lastname.value
        })
    })
});