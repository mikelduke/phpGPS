# -*- mode: ruby -*-
# vi: set ft=ruby :


NODE_IP = "172.17.4.99"
NODE_VCPUS = 2
NODE_MEMORY_SIZE = 2048

Vagrant.configure("2") do |config|
  config.vm.box = "ubuntu/bionic64"

  config.vm.provider :virtualbox do |v|
    v.cpus = NODE_VCPUS
    v.gui = false
    v.memory = NODE_MEMORY_SIZE
  end

  config.vm.network :private_network, ip: NODE_IP
  config.vm.network :forwarded_port, guest: 80, host: 80
  
  config.vm.provision :shell, path: "vagrant-install.sh"

  config.vm.synced_folder "src/", "/var/www/html/"
end
