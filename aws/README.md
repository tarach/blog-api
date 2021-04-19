```cat ~/.aws/credentials```
* export AWS_ACCESS_KEY_ID=
* export AWS_SECRET_ACCESS_KEY=

```
terraform apply -auto-approve

eksctl create cluster --fargate --nodes-max 2 --name tutorial --verbose 5 --vpc-private-subnets <subnet-id-1>,<subnet-id-2>

eksctl create cluster --fargate --nodes-max 2 --name tutorial --region eu-central-1 --zones=eu-central-1a,eu-central-1b --verbose 5
```
