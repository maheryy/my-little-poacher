import { render, screen } from "@testing-library/vue";
import Scanner from "src/views/protected/pro/Scanner.vue";
import tickets from "mock_data/models/tickets.json";
import { defaultRenderOptions } from "../../../config";
import userEvent from "@testing-library/user-event";
import server from "../../../../src/mocks/server";
import { rest } from "msw";

describe("The ticket scanner page", () => {
  it("should display the ticket reference as title", () => {
    render(Scanner, { ...defaultRenderOptions });
    expect(screen.getByText("Ticket verification")).toBeInTheDocument();
  });

  it("should not show the scanner by default", () => {
    render(Scanner, { ...defaultRenderOptions });
    expect(screen.queryByTestId("reader")).not.toBeInTheDocument();
  });

  describe("Manual ticket verification", () => {
    it("should display a 'Verify' CTA button", () => {
      render(Scanner, { ...defaultRenderOptions });
      expect(screen.getByText("Verify")).toBeInTheDocument();
    });

    it("should display an error when typing wrong reference", async () => {
      render(Scanner, { ...defaultRenderOptions });

      await userEvent.type(screen.getByLabelText("Reference"), "hello");
      await userEvent.click(screen.getByText("Verify"));

      expect(
        await screen.findByText("The reference must be 10 characters long")
      ).toBeInTheDocument();
    });

    it("should have the ticket validated when verifying a valid reference", async () => {
      server.use(
        rest.get("/tickets/verify/:reference", (req, res, ctx) => {
          return res(ctx.json({ success: true, message: "Ticket validated" }));
        })
      );
      render(Scanner, { ...defaultRenderOptions });

      await userEvent.type(screen.getByLabelText("Reference"), "1234567890");
      await userEvent.click(screen.getByText("Verify"));

      expect(await screen.findByText("Ticket validated")).toBeInTheDocument();
    });

    it("should display the error response from the server when providing an invalid reference", async () => {
      server.use(
        rest.get("/tickets/verify/:reference", (req, res, ctx) => {
          return res(
            ctx.json(
              { success: false, message: "Invalid ticket" },
              ctx.status(400)
            )
          );
        })
      );
      render(Scanner, { ...defaultRenderOptions });

      await userEvent.type(screen.getByLabelText("Reference"), "1111111111");
      await userEvent.click(screen.getByText("Verify"));

      expect(await screen.findByText("Invalid ticket")).toBeInTheDocument();
    });
  });

  describe("QR code scanner", () => {
    it("should display a 'Use the scanner' CTA button ", () => {
      render(Scanner, { ...defaultRenderOptions });

      expect(screen.getByText("Use the scanner")).toBeInTheDocument();
    });
  });
});
