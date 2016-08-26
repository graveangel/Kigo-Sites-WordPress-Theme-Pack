app.events = 
        {
            /**
             * To be triggered when the header is set with class fixed
             * @type CustomEvent
             */
            headerFixed: new CustomEvent('headerFixed', {bubbles: true, cancelable: false}),
            /**
             * To be triggered when the fixed header class is removed from it
             * @type CustomEvent
             */
            headerUnfixed: new CustomEvent('headerunFixed', {bubbles: true, cancelable: false}),
        }