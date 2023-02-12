import { render, screen, waitFor } from "@testing-library/vue";
import TicketPostCheckout from "src/views/protected/TicketPostCheckout.vue";
import { defaultRenderOptions } from "../../config";

describe("The post checkout loading page", () => {
  it("should display the main title", () => {
    render(TicketPostCheckout, { ...defaultRenderOptions });
    expect(screen.getByText("Payment confirmation")).toBeInTheDocument();
  });

  it("should display a loading text content on page load", () => {
    render(TicketPostCheckout, { ...defaultRenderOptions });
    expect(
      screen.getByText("Payment verification in progress...")
    ).toBeInTheDocument();
  });

  it("should display a redirection message when the payment has been confirmed", async () => {
    render(TicketPostCheckout, { ...defaultRenderOptions });

    expect(
      await screen.findByText("You will be redirected to your tickets...")
    ).toBeInTheDocument();
  });
});
