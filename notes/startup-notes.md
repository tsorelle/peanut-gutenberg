sequence: (see: https://austinquakers.org/application/docs/notes/startup-sequence.html)
route to services where needed
all pages start: bootstrap peanut
block render: find view model info and render view
end of page render starup js to load viewmodels

Concretecms examples:
routing:
web.root/application/bootstrap/peanut-app.php
Package start:
web.root/packages/knockout_view/controller.php: on_start()
inserts javascript tags for head.js and peanutLoader
block rendering:
web.root/packages/knockout_view/blocks/knockout_view/controller.php: view()
$vmInfo = ViewModelManager::getViewModelSettings($this->viewmodel,$this->bID);
web.root/packages/knockout_view/blocks/knockout_view/view.php
viewmodel load:
web.root/application/themes/fma/elements/footer_bottom.php

todo:
- Figure where to put core js script tags
- Figure out where to put view model load rendering: theme? block?
- test display the viewmodel property on the component
- test peanut php classes avaliable when block renders
- test $vmInfo = ViewModelManager::getViewModelSettings($this->viewmodel,$this->bID);
- test viewmodel load rendering
- complete block rendering implementation and test with simple vms
- Implement plugin classes for classes.ini
  [tops.userfactory]
  [tops.connections] (connections manager)
  [tops.permissions]
  [tops.maildistribution]
  [peanut.subscription_manager]

Questions:
which peanut and qnut routines to implement as blocks or peanut pages?
	




