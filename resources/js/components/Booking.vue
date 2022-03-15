<template>
    <div class="content">
        <div class="title header-color">
            <h1>Simple Booking App</h1>
        </div>
        <div class="new-appointment">
            <div class="new-appointment-name">
                Name: <input v-model="name" placeholder="Appointment Name">
            </div>
            <div class="new-appointment-date">
                Date: <datepicker v-model="datePicked" />
            </div>
            <div class="new-appointment-add">
                <button @click="addAppointment">Add Appointment</button>
            </div>
        </div>
        <div>
            <h3>To edit an appointment, set the name and date, and then click the edit button on the appointment you wish to edit.</h3>
        </div>
        <div class="future-appointments">
            <h2 class="header-color">Future Appointments:</h2>
            <table id="future">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Name</th>
                        <th />
                        <th />
                    </tr>
                </thead>
                <tbody>
                    <tr :key="row" v-for="row in futureAppointments.rows">
                        <td>{{ row.date }}</td>
                        <td>{{ row.name }}</td>
                        <td>
                            <button @click="editAppointment(row.id)">Edit</button>
                        </td>
                        <td>
                            <button @click="deleteAppointment(row.id)">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="past-appointments">
            <h2 class="header-color">Past Appointments:</h2>
            <table id="past">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Name</th>
                        <th />
                        <th />
                    </tr>
                </thead>
                <tbody>
                    <tr :key="row" v-for="row in pastAppointments.rows">
                        <td>{{ row.date }}</td>
                        <td>{{ row.name }}</td>
                        <td>
                            <button @click="editAppointment(row.id)">Edit</button>
                        </td>
                        <td>
                            <button @click="deleteAppointment(row.id)">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <footer />
</template>

<style>
    html, body {
        background-color: #1d3557;
        color: #ffffff;
        height: 100vh;
        margin: 0;
        font-size: 25px;
    }

    footer {
        padding-top: 50px;
    }

    table {
        border-collapse: collapse;
        margin: 10px 10px 0 10px;
        text-align: left;
        overflow-x: auto;
        min-width: 80vw;
    }

    table th {
        padding: 8px;
        background-color: #13233b;
    }

    table td {
        padding: 8px;
    }

    table tr:nth-child(even) {
        background-color: #13233b;
    }

    table tr:nth-child(odd) {
        background-color: #1b3252;
    }

    .content {
        align-items: center;
        flex-direction: column;
        display: flex;
        justify-content: center;
        position: relative;
    }

    .title {
        font-size: 35px;
    }

    .header-color {
        color: #e63946; 
    }

    .new-appointment {
        padding-top: 30px;
    }

    .new-appointment-date {
        display: inline-flex;
        gap: 5px;
    }

    .new-appointment-add {
        padding-top: 20px;
    }

    .past-appointments {
        padding-top: 50px;
    }
</style>

<script setup>
import axios from "axios"
import Datepicker from "vue3-datepicker"
import { reactive } from "vue"
import { ref } from "vue"

// Minimize URL boilerplate
const http = axios.create({
    baseURL: "http://localhost/api",
    headers: {
        "Content-type": "application/json"
    }
});

const currentDate = new Date()
const datePicked = ref(new Date())
const name = ref("")

// At first I figured this would be the simplest way to manage the appointment state while minimizing network traffic
// This unfortunately led to the delete and edit operations being much more complicated and ugly than they should be
// Given more time, I would definitely refactor this
const pastAppointments = reactive({
    rows: []
})
const futureAppointments = reactive({
    rows: []
})

// Retrieve all appointments from the backend
http.get("/appointments")
    .then((response) => {
        // Sort the appointments into the past and future arrays
        response.data.data.forEach(appointment => {
            if (new Date(appointment.date) >= currentDate) {
                futureAppointments.rows.push( {id: appointment.id, date: appointment.date, name: appointment.name });
            } else {
                pastAppointments.rows.push( {id: appointment.id, date: appointment.date, name: appointment.name });
            }
        });
        // Sort appointments by date
        // I don't know why this isn't working
        futureAppointments.rows.sort(function compare(a, b) { a.date > b.date ? 1 : a.date < b.date ? -1 : 0 });
        pastAppointments.rows.sort(function compare(a, b) { a.date > b.date ? 1 : a.date < b.date ? -1 : 0 });
    }, (error) => {
        alert("Error fetching appointments!");
});


function addAppointment() {
    // datePicked could potentially change in between the POST request being made and a response
    // being received, so we create a new Date object using dateFormatted, as it's guaranteed to be the same
    const dateCached = datePicked.value;
    
    // Format the date to be YYYY-MM-DD, ignoring time
    let dateFormatted = String(dateCached.getFullYear()) + "-";
    if (dateCached.getMonth() + 1 < 10) {
        dateFormatted += "0" + (dateCached.getMonth() + 1) + "-";
    } else {
        dateFormatted += (dateCached.getMonth() + 1) + "-";
    }
    if (dateCached.getDate() < 10) {
        dateFormatted += "0" + dateCached.getDate();
    } else {
        dateFormatted += dateCached.getDate();
    }
    
    http.post("/add", { date: dateFormatted, name: name.value })
        .then((response) => {
            if(dateCached >= currentDate) {
                futureAppointments.rows.push({ id: response.data, date: dateFormatted, name: name.value });
            } else {
                pastAppointments.rows.push({ id: response.data, date: dateFormatted, name: name.value });
            }
        }, (error) => {
            alert("Error creating appointment!");
    });
}

function deleteAppointment(id) {
    // Redefine axios options since we're not sending JSON
    const options = {
        method: "delete",
        url: "http://localhost/api/delete",
        data: "id=" + id,
        headers: {
        "Content-type": "application/x-www-form-urlencoded"
        }
    };
    axios(options)
        .then((response) => {
            // This should definitely be refactored
            const futureIndex = futureAppointments.rows.findIndex(appointment => { return appointment.id === id });
            if (futureIndex > -1) {
                futureAppointments.rows.splice(futureIndex, 1);
            }
            const pastIndex = pastAppointments.rows.findIndex(appointment => { return appointment.id === id });
            if (pastIndex > -1) {
                pastAppointments.rows.splice(pastIndex, 1);
            }
        },
            (error) => {
                alert("Error deleting appointment!");
    });
}

function editAppointment(id) {
    const dateCached = datePicked.value;
    // Also cache the name for the same reason as the date
    const nameCached = name.value;
    
    let dateFormatted = String(dateCached.getFullYear()) + "-";
    if (dateCached.getMonth() + 1 < 10) {
        dateFormatted += "0" + (dateCached.getMonth() + 1) + "-";
    } else {
        dateFormatted += (dateCached.getMonth() + 1) + "-";
    }
    if (dateCached.getDate() < 10) {
        dateFormatted += "0" + dateCached.getDate();
    } else {
        dateFormatted += dateCached.getDate();
    }

    const options = {
        method: "patch",
        url: "http://localhost/api/update",
        data: "id=" + id + "&date=" + dateFormatted + "&name=" + name.value,
        headers: {
        "Content-type": "application/x-www-form-urlencoded"
        }
    };
    axios(options)
        .then((response) => {
            // Find where the appointment is so that it can be updated
            // However, it may also need to be moved to the other array due to the date changing
            // To make the code simpler, the appointment is always removed and replaced
            // This is very messy and bad for many reasons
            const futureIndex = futureAppointments.rows.findIndex(appointment => { return appointment.id === id });
            if (futureIndex > -1) {
                futureAppointments.rows.splice(futureIndex, 1);
            }
            const pastIndex = pastAppointments.rows.findIndex(appointment => { return appointment.id === id });
            if (pastIndex > -1) {
                pastAppointments.rows.splice(pastIndex, 1);
            }

            let appointment = { id: id, date: dateFormatted, name: nameCached };
            if(dateCached >= currentDate) {
                futureAppointments.rows.push(appointment);
            } else {
                pastAppointments.rows.push(appointment);
            }
        }, (error) => {
            alert("Error editing appointment!");
    });
}
</script>
