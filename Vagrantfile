# -*- mode: ruby -*-
# vi: set ft=ruby :

# All Vagrant configuration is done below. The "2" in Vagrant.configure
# configures the configuration version (we support older styles for
# backwards compatibility). Please don't change it unless you know what
# you're doing.
Vagrant.configure("2") do |config|
  config.vm.define "das-dev"
  config.vm.box = "ubuntu/focal64"
  config.vm.provision :shell, path: "vagrant/bootstrap.sh"
  config.vm.network :forwarded_port, guest: 80, host: 80
  config.vm.network :forwarded_port, guest: 443, host: 443
  config.hostsupdater.aliases = ["caslink.dev", "caslinksaml.dev"]
  config.vm.hostname = "das.dev"
  config.vm.provider "virtualbox" do |vb|
      vb.customize ["modifyvm", :id, "--uart1", "0x3F8", "4"]
      vb.customize ["modifyvm", :id, "--uartmode1", "file", File::NULL]
  end
end
