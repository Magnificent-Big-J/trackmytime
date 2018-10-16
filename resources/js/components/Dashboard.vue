<template>
    <div class="card">
        <div class="card-header bg-primary">Dashboard</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="well dash-box">
                        <h2><i class="fas fa-tasks"></i> {{complete}}</h2>
                        <h4>Completed</h4>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="well dash-box">
                        <h2><i  class="fas fa-adjust"></i> {{incomplete}}</h2>
                        <h4>In progress</h4>
                    </div>
                </div>


            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "dashboard",
        data(){
            return{
                complete:0,
                incomplete:0
            }
        },
        methods:{
            get_stats()
            {
                axios.get('/complete')
                    .then((response)=>{
                        this.complete = response.data.complete
                        this.incomplete = response.data.incomplete

                    })
                    .catch((error)=>{
                        console.log(error)
                    })
            }
        },
        created(){
            this.get_stats()
        }
    }
</script>

<style scoped>

</style>