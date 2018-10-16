<template>
    <div class="card">
        <div class="card-header">
            Projects Break Down <button class="btn btn-xs btn-info float-right" @click="updateDuration">Update Project Time</button>
        </div>
        <div class="card-body">
            <table class="table table-hover table-bordered">
                <thead>
                    <th>Project Name</th>
                    <th>Duration</th>
                    <th>Status</th>
                    <th>Actions</th>
                </thead>
                <tbody>
                    <tr v-for="project in projects">
                        <td>{{project.name}}</td>
                        <td>{{project.duration}}</td>
                        <td>
                            <span class="lead" v-if="project.status">Complete</span>
                            <span class="lead" v-else>In progress</span>
                        </td>
                        <td>
                            <button class="btn btn-xs btn-primary" v-if="!project.status" @click="markAsComplete(project.id)">Mark As Complete</button>
                            <button class="btn btn-xs btn-danger"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        name: "break-down",
        data(){
            return{
                projects:{}
            }
        },
        methods:{
            get_projects()
            {
                axios.get('/breakdown')
                    .then((response)=>{
                        this.projects = response.data
                    })
                    .catch((error)=>{
                        console.log(error)
                    })
            },
            updateDuration()
            {
                axios.get('/updateDuration')
                    .then((response)=>{
                                                swal(
                        'Updated',
                        response.data.message,
                        'success')
                    })
                    .catch((error)=>{
                        console.log(error)
                    })
            },
            markAsComplete(id){

                axios.get('/markasComplete/'+id)
                    .then((response)=>{
                    swal(
                    'Updated',
                    response.data.message,
                            'success')
                    })
                    .catch((error)=>{
                            console.log(error)
                    })
            }

        },
        created(){
            this.get_projects()
        }
    }
</script>

<style scoped>

</style>