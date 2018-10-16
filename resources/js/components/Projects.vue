<template>
    <div class="col-md-12 ">
        <div class="no-projects" v-if="projects">

            <div class="row">
                <div class="col-sm-12">
                    <h2 class="float-left project-title">Projects</h2>
                    <button class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#projectCreate">New Project</button>
                </div>
            </div>

            <hr>

            <div v-if="projects.length > 0">
                <div class="card mt-2" v-for="project in projects" :key="project.id">
                    <div class="card-header clearfix">
                        <h4 class="float-left">{{ project.name }}</h4>

                        <button class="btn btn-success btn-sm float-right" :disabled="counter.timer" data-toggle="modal" data-target="#timerCreate" @click="selectedProject = project">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>

                    <div class="card-body">
                        <ul class="list-group" v-if="project.timers.length > 0">
                            <li v-for="timer in project.timers" :key="timer.id" class="list-group-item clearfix">
                                <strong class="timer-name">

                                    {{ timer.name }}
                                        <span class="float-right">
                                        <button class="btn btn-xs btn-info" data-toggle="modal" data-target="#taskInfo" @click="TaskInfo(project,timer)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                            </button>
                                            <button class="btn btn-xs btn-primary"   @click="editTask(timer)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                            <button class="btn btn-xs btn-danger" @click="deleteTask(timer.id)">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>

                                        </span>


                                </strong>
                                <div class="float-right">

                                        <span v-if="showTimerForProject(project, timer)" style="margin-right: 10px">
                                            <strong>{{ activeTimerString }}</strong>
                                        </span>
                                        <span v-else style="margin-right: 10px">
                                            <strong>{{ calculateTimeSpent(timer) }}</strong>
                                        </span>
                                    <button v-if="showTimerForProject(project, timer)" class="btn btn-sm btn-danger" @click="stopTimer()">
                                        <i class="fas fa-stop-circle"></i>
                                    </button>
                                </div>
                            </li>
                        </ul>
                        <p v-else>Nothing has been recorded for "{{ project.name }}".Click the cross to add a task then start.</p>
                    </div>
                </div>
                <!-- Create Timer Modal -->
                <div class="modal fade" id="timerCreate" role="dialog">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"> <i class="fas fa-times-circle"></i></button>
                                <h4 class="modal-title">Start Your Task</h4>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <input v-model="newTimerName" type="text" class="form-control" id="usrname" placeholder="What are you working on?">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button data-dismiss="modal" v-bind:disabled="newTimerName === ''" @click="createTimer(selectedProject)" type="submit" class="btn btn-default btn-primary"><i class="fas fa-play-circle"></i> Start</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else>
                <h3 class="text-center">You need to create a new project</h3>
            </div>
            <!-- Create Project Modal -->
            <div class="modal fade" id="projectCreate" role="dialog">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"> <i class="fas fa-tomes-circle"></i> </button>
                            <h4 class="modal-title text-left">New Project</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <input v-model="newProjectName" type="text" class="form-control" id="usrname" placeholder="Project Name">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button data-dismiss="modal" v-bind:disabled="newProjectName == ''" @click="createProject" type="submit" class="btn btn-default btn-primary">Create</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="timers" v-else>
            Loading...
        </div>
        <div class="modal fade" id="taskInfo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Task Info</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p class="lead">Project Name:{{taskInfo.project_name}}</p>
                        <p class="lead">Task Name:{{taskInfo.task_name}}</p>
                        <p class="lead">Period:{{taskInfo.start_time}} - {{taskInfo.stopped_time}}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editTaskInfo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Task Name</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="text" name="name" class="form-control"  v-model="task.name">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="updateInfo">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import moment from 'moment'
    export default{
        data() {
            return {
                task:{id:null,name:null},

                projects: null,
                newTimerName: '',
                newProjectName: '',
                activeTimerString: 'Calculating...',
                counter: { seconds: 0, timer: null },
                taskInfo:{project_name:null,task_name:null,start_time:null,stopped_time:null}
            }
        },
        methods:{
            loadProjects(){
                axios.get('/projects')
                    .then((response)=>{
                            this.projects = response.data
                            axios.get('/project/timers/active')
                                .then((response)=>{
                                if (response.data.id !== undefined) {
                                        this.startTimer(response.data.project, response.data)
                                    }
                                })
                                .catch((error)=>{
                                    console.log(error)
                                })
                    })
                    .catch((error)=>{
                        console.log(error)
                    })
            },
            /**
             * Conditionally pads a number with "0"
             */
            _padNumber: number =>  (number > 9 || number === 0) ? number : "0" + number,

        /**
         * Splits seconds into hours, minutes, and seconds.
         */
        _readableTimeFromSeconds(seconds) {
            const hours = 3600 > seconds ? 0 : parseInt(seconds / 3600, 10)
            return {
                hours: this._padNumber(hours),
                seconds: this._padNumber(seconds % 60),
                minutes: this._padNumber(parseInt(seconds / 60, 10) % 60),
            }
        },

        /**
         * Calculate the amount of time spent on the project using the timer object.
         */
        calculateTimeSpent(timer) {
            if (timer.ended_at) {
                const started = moment(timer.started_at)
                const stopped = moment(timer.ended_at)
                const time = this._readableTimeFromSeconds(
                    parseInt(moment.duration(stopped.diff(started)).asSeconds())
                )
                return `${time.hours} Hours | ${time.minutes} mins | ${time.seconds} seconds`
            }
            return ''
        },

        /**
         * Determines if there is an active timer and whether it belongs to the project
         * passed into the function.
         */
        showTimerForProject(project, timer) {
            return this.counter.timer &&
                this.counter.timer.id === timer.id &&
                this.counter.timer.project.id === project.id
        },

        /**
         * Start counting the timer. Tick tock.
         */
        startTimer(project, timer) {
            const started = moment(timer.started_at)

            if(timer.ended_at)
            {
                let now = moment(timer.started_at);
                let then = moment(timer.ended_at);

                console.log(moment(moment.duration(now.diff(then))).format("hh:mm:ss"))

                return;

            }


            let vm = this
            this.counter.timer = timer
            this.counter.timer.project = project
            this.counter.seconds = parseInt(moment.duration(moment().diff(started)).asSeconds())
            this.counter.ticker = setInterval(() => {
                const time = this._readableTimeFromSeconds(++vm.counter.seconds)

                this.activeTimerString = `${time.hours} Hours | ${time.minutes}:${time.seconds}`
        }
        , 1000)
        },

        /**
         * Stop the timer from the API and then from the local counter.
         */
        stopTimer() {
           axios.post(`/projects/${this.counter.timer.id}/timers/stop`)
                .then(response => {
                // Loop through the projects and get the right project...
                this.projects.forEach(project => {
                if (project.id === parseInt(this.counter.timer.project.id)) {
                // Loop through the timers of the project and set the `ended_at` time
                return project.timers.forEach(timer => {
                    if (timer.id === parseInt(this.counter.timer.id)) {
                    return timer.ended_at = response.data.ended_at
                }
            })
            }
        });

            // Stop the ticker
            clearInterval(this.counter.ticker)

            // Reset the counter and timer string
            this.counter = { seconds: 0, timer: null }
            this.activeTimerString = 'Calculating...'
        })
        },

        /**
         * Create a new timer.
         */
        createTimer(project) {
            axios.post(`/projects/${project.id}/timers`, {name: this.newTimerName})
                .then(response => {
                swal(
                'Created',
                'Task ' + this.newTimerName + ' is successfully created',
                'success'
        )
                project.timers.push(response.data)
            this.startTimer(response.data.project, response.data)
        })

            this.newTimerName = ''
        },

        /**
         * Create a new project.
         */
        createProject() {
           axios.post('/projects', {name: this.newProjectName})
                .then(response => this.projects.push(response.data))
            swal(
                'Created',
                'Project ' + this.newProjectName + ' is successfully created',
                'success'
            )
            this.newProjectName = ''
        },
            TaskInfo(project,timer)
            {
                this.taskInfo.project_name = project.name
                this.taskInfo.task_name = timer.name
                this.taskInfo.start_time = timer.started_at
                this.taskInfo.stopped_time = timer.ended_at
            },
        deleteTask(id){
            swal({
                title:'Are you sure',
                text:"You won't be able to recover this",
                type:'warning',
                showCancelButton:true,
                confirmButtonColor:'#3085d6',
                cancelButtonColor:'#d33',
                confirmButtonText:'Yes, Delete It'
            }).then((result)=>{
                //send request to the server
                if(result.value)
            {
                axios.delete('projects/'+id +'/delete')
                    .then((response)=>{
                        this.loadProjects()
                    swal(
                    'Deleted',
                    response.data.message,
                    'success'
            )

            })
            .catch((error)=>{
                console.log(error)
            })
            }


        })
        },
        editTask(timer){
            $("#editTaskInfo").modal('show')
           this.task.id =  timer.id
            this.task.name = timer.name
        },
        updateInfo(){
            axios.put('/projects/'+this.task.id+'/update',{name:this.task.name})
                .then((response)=>{
                this.loadProjects()
                $("#editTaskInfo").modal('hide')
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
            this.loadProjects()
        }

    }
</script>

<style scoped>
    .project-title {
        margin: 0;
    }
    .card-header > h4 {
        margin:0;
        position: relative;
        top: 6px;
    }
</style>