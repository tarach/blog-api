terraform {
  required_providers {
    aws = {
      source  = "hashicorp/aws"
      version = "~> 3.27"
    }
  }

  backend "s3" {
    bucket = "devops5"
    key    = "terraform/blog-api/vpc"
    region = "eu-central-1"
  }
}

provider "aws" {
  profile = "default"           # this the profile which we created using aws configure cli command
  region  = "eu-central-1"        # this will make default region as ap-south-1 which is in mumbai
}

module "my_vpc" {
  source = "./create-vpc"
}

# NAT gateway
resource "aws_nat_gateway" "nat_gateway" {
  allocation_id = module.my_vpc.eip_id
  subnet_id     = module.my_vpc.subnet_pub_id

  tags = {
    Name = "nat-gateway"
  }
}

# route table with target as NAT gateway
resource "aws_route_table" "NAT_route_table" {
  depends_on = [
    aws_nat_gateway.nat_gateway,
  ]

  vpc_id = module.my_vpc.vpc_id

  route {
    cidr_block = "0.0.0.0/0"
    gateway_id = aws_nat_gateway.nat_gateway.id
  }

  tags = {
    Name = "NAT-route-table"
  }
}

# associate route table to private subnet
resource "aws_route_table_association" "associate_routetable_to_private_subnet1" {
  subnet_id      = module.my_vpc.subnet_priv_id1
  route_table_id = aws_route_table.NAT_route_table.id
}
resource "aws_route_table_association" "associate_routetable_to_private_subnet2" {
  subnet_id      = module.my_vpc.subnet_priv_id2
  route_table_id = aws_route_table.NAT_route_table.id
}

output "subnet_priv_id1" {
  value = module.my_vpc.subnet_priv_id1
}

output "subnet_priv_id2" {
  value = module.my_vpc.subnet_priv_id2
}
