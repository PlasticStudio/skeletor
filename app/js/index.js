
// Compile our scss
// This 'includes' the SCSS index file which webpack then reads and
// compiles into the necessary css files
require('../scss/index.scss');

// Inject our components
require('./components/navigation.js');
require('./components/togglable-content.js');
