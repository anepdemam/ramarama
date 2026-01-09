<script src="https://cdn.jotfor.ms/s/umd/latest/for-embedded-agent.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Initialize the chat widget
        window.AgentInitializer.init({
            rootId: "jotform-button",
            formID: "01971587ca117574a595b9628362f585ee0c",
            queryParams: ["skipWelcome=1", "maximizable=1"],
            domain: "https://www.jotform.com",
            isInitialOpen: false,
            isDraggable: false,
            background: "linear-gradient(180deg, #D3CBF4 0%, #D3CBF4 100%)",
            buttonBackgroundColor: "#8797FF",
            buttonIconColor: "#01091B",
            variant: false,
            customizations: {
                greeting: "Yes",
                greetingMessage: "Hi, Welcome to Ramarama.co. How can I help you today?",
                pulse: "Yes",
                position: "right"
            }
        });

        // Apply custom styles after load
        const styleObserver = new MutationObserver(function () {
            const frame = document.getElementById('JotformEmbeddedAgentFrame');
            const container = document.getElementById('JotformEmbeddedAgentChatContainer');

            if (frame && container) {
                styleObserver.disconnect();

                frame.style.cssText = `
        width: 150px !important;
        height: 200px !important;
        border-radius: 12px !important;
        box-shadow: 0 5px 25px rgba(0,0,0,0.15) !important;
        border: none !important;
      `;

                container.style.cssText = `
        right: 20px !important;
        bottom: 80px !important;
        left: auto !important;
      `;
            }
        });

        styleObserver.observe(document.body, {
            childList: true,
            subtree: true,
            attributes: true
        });
    });
</script>