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
        <div class="past-appointments">
            <h2 class="header-color">Past Appointments:</h2>
            <table id="past">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Name</th>
                    </tr>
                </thead>
                <tbody>
                    <tr :key="row" v-for="row in pastAppointments.rows">
                        <td>{{ row.date }}</td>
                        <td>{{ row.name }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<style>
    html, body {
        background-color: #dce1e3;
        color: #5c5f58;
        height: 100vh;
        margin: 0;
        font-size: 25px;
    }

    table {
        border-collapse: collapse;
        border: 5px solid #5c5f58;
        margin: 10px 10px 0 10px;
        text-align: center;
        
    }

    table th {
        padding: 8px;
        min-width: 30px;
        border-right: 3px solid #5c5f58;
    }

    table td {
        padding: 8px;
        border-right: 3px solid #5c5f58;
    }

    table tr:nth-child(even) {
        background-color: #ffffff;
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
        color: #b73225; 
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
import Datepicker from 'vue3-datepicker'
import { reactive } from 'vue'
import { ref } from 'vue'

// The first 9 characters of an ISO date string are the year, month, and day
// We don't care about the time so we won't store it
const currentDate = new Date().toISOString.substring(0, 10);
const datePicked = ref(new Date())
const name = ref('')

const pastAppointments = reactive({
    rows: []
});
const futureAppointments = reactive({
    rows: []
})

function addAppointment() {
    const dateFormatted = datePicked.value.toISOString().substring(0, 10);
    pastAppointments.rows.push({ "date": dateFormatted, "name": name.value });
}
</script>
