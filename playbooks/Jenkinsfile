pipeline {
    agent any
    environment {
        SERVER_IP = "13.215.209.74"
        PROJECT_NAME = "laravel_node_server"
        WORKSPACE_PATH = "/var/lib/jenkins/workspace/${PROJECT_NAME}"
        REMOTE_PATH = "~/deploy/${PROJECT_NAME}"
    }
    
    stages {
        stage("Copy files to Docker server") {
            steps {
                sh "scp -r ${WORKSPACE_PATH}/* root@${SERVER_IP}:${REMOTE_PATH}"
            }
        }

        stage("Build Docker Image") {
            steps {
                ansiblePlaybook playbook: "${WORKSPACE_PATH}/playbooks/build.yml"
            }    
        } 

        stage("Deploy Docker Containers") {
            steps {
                ansiblePlaybook playbook: "${WORKSPACE_PATH}/playbooks/deploy.yml"
            }    
        } 
    }

    post {
        success {
            echo "✅ Laravel Deployment Successful!"
        }
        failure {
            echo "❌ Laravel Deployment Failed!"
        }
    }
}
