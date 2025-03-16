pipeline {
    agent any
    environment {
        SERVER_IP = "47.129.60.53"
        PROJECT_NAME = "laravel"
        WORKSPACE_PATH = "/var/lib/jenkins/workspace/${PROJECT_NAME}"
        REMOTE_PATH = "~/${PROJECT_NAME}"
    }
    
    stages {
        stage("Copy files to Docker server") {
            steps {
                sh "scp -r /var/lib/jenkins/workspace/laravel/* root@47.129.60.53:~/laravel"
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
