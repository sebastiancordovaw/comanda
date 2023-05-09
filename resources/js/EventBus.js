import mitt from "mitt";

export default mitt()


/*You should use a signleton :
npm install --save mitt

EventBus.js :

import mitt from "mitt";

export default mitt()

Then, on your component or on your pinia store :

import EventBus from "../lib/EventBus";
...

EventBus.emit(...)
EventBus.on(...)
...
*/
