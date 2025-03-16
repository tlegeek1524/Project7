pipeline {
    agent any
    environment {
        SERVER_IP = "13.215.209.74"
        PROJECT_NAME = "Project7"
        WORKSPACE_PATH = "/var/lib/jenkins/workspace/${PROJECT_NAME}"
        REMOTE_PATH = "~/${PROJECT_NAME}"
    }
    
    stages {
        stage("Copy files to Docker server") {
            steps {
                sh "scp -r /var/lib/jenkins/workspace/Project7/* root@13.215.209.74:~/Project7"
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
